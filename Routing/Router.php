<?php
    namespace App\Routing;

    class Router {
        private array $routes;
        private array $dispatched;

        public function __construct() {}
        
        public function get() {
            return $this->routes;
        }
        public function add(string $url, string $method, array $handler) {
            $this->routes[] = new Route($url, $method, $handler);
        }

        public function dispatch() {
            $url = filter_input(INPUT_GET, "URL");
            $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");

            try {
               
                $this->dispatched["route"] = $this->find($url, $method);
                $this->dispatched["args"]  = $this->find($url, $method)->isolate($url);
                
            }
            catch (\Exception $err) {
                echo $err->getMessage();
            }
        }

        public function getRoute() {
            return $this->dispatched["route"];
        }

        public function getArguments() {
            return $this->dispatched["args"];
        }

        public function callHandle(Router $router): void {
            handle($router);
        }

        private function &find($url, $method): ?Route {
            foreach ($this->routes as $route) {
                if ($route->isMatch($url, $method)) {
                    return $route;
                }
            }
            return null;
        }
    }
?>