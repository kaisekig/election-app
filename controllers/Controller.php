<?php
    namespace App\Controllers;
    
    use App\Config\DatabaseConnection;
    use App\Core\Session\Session;

    require_once './vendor/autoload.php';

    abstract class Controller {
        private $connection;
        private $data = [];
        private $session;

        public function __construct(DatabaseConnection &$connection)
        {
            $this->connection = $connection;
        }

        public function &getConnect(): DatabaseConnection {
            return $this->connection;
        }

        public function get(): array {
            return $this->data;
        }

        public function set(string $key, $value): void {
            $this->data[$key] = $value;
        }

        public function view(string $templatePath, string $file, array $data = []) {
            $loader = new \Twig\Loader\FilesystemLoader($templatePath);
            $twig = new \Twig\Environment($loader, []);

            echo $twig->render($file, $data);
        }

        public function redirect(string $url, int $code = 303) {
            header("Location: " . $url);
            exit;
        }

        public function setSession(Session &$session) {
            $this->session = $session;
        }

        public function &getSession(): Session {
            return $this->session;
        }
    }
?>