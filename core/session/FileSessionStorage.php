<?php
    namespace App\Core\Session;

    class FileSessionStorage implements SessionStorage {
        private $path;

        public function __construct(string $path)
        {
            $this->path = $path;
        }
        public function save(string $sessionId, string $sessionData) {
            $filename = $this->path . $sessionId . ".json";
            file_put_contents($filename, $sessionData);
        }

        public function load(string $sessionId): string {
            $filename = $this->path . $sessionId . ".json";
            if (!file_exists($filename)) {
                return "{}";
            }

            return file_get_contents($filename);
            
        }

        public function delete(string $sessionId) {
            $filename = $this->path . $sessionId . ".json";
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }
?>