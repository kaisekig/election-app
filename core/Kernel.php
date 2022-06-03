<?php
    namespace App\Core;

    use App\Config\DatabaseConnection;
    use App\Core\Session\Session;

    class Kernel {
        private array $objects;
        private $controller;

        private DatabaseConnection $databaseConnection;
        private object $session;

        public function __construct(DatabaseConnection $databaseConnection, array $molds) 
        {
            $this->databaseConnection = $databaseConnection;

            foreach ($molds as $mold) {
                $object = new $mold($this->databaseConnection);
                $this->objects[] = $object;
            }
            
            $this->session = (object) null;
        }

        public function setSession(Session $session) {
            $this->session = $session;
        }

        public function getSession() {
            return $this->session;
        }

        public function rebuild(array $data): ?array {
           $this->build($data);

           $this->controller->setSession($this->session);
           $this->controller->getSession()->reload();
           [$class, $action] = $data;
           
           return [$this->controller, $action];
        }

        private function build(array $data) {
            if (!is_array($data)) {
                return null;
            }

            [$class, $action] = $data;
            foreach ($this->objects as $object) {
                try {
                    if (get_class($object) === $class) {
                        $this->controller =  new $class($this->databaseConnection);
                    }
                } catch (\Exception $error) {
                    return $error->getMessage();
                }
                
            }
        }
    }
?>