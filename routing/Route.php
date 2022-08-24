<?php
    namespace App\Routing;
    
    class Route {
        private string $url;
        private string $method;
        private array $handler;

        public function __construct(string $url, string $method, array $handler)
        {
            $this->url = $url;
            $this->method = $method;
            $this->handler = $handler;

        }

        public function isMatch(?string $url, string $method) {
            # Passing null to parameter #2 ($subject) of type string is deprecated
            if ($url === null) {
                $url = "";

                if (!preg_match($this->url, $url)) {
                    return false;
                }
            }

            if (!preg_match($this->url, $url)) {
                return false;
            }
        
            
            if (!preg_match("|^" . $this->method .  "$|", $method)) {
                return false;
            }

            return true;
        }

        public function isolate(?string $url): ?array {
            $matches = [];
            $args    = [];
    
            if ($url === null) {
                $url = "";

                preg_match_all("|(\d)+|", $url, $matches);
            } else {
                preg_match_all("|(\d)+|", $url, $matches);
            }
    
            if ($matches[0][0]) {
                array_push($args, $matches[0][0]);
    
                if (count($matches[0]) > 1) {
                    array_push($args, $matches[0][1]);
                    return $args;
                }
    
                return $args;
    
            }
    
            return $args;
        }

        public function getHandler(): array {
            return $this->handler;
        }
    }
?>