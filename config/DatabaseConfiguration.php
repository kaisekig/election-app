<?php
    namespace App\Config;

    class DatabaseConfiguration {
        private $driver;
        private $host;
        private $username;
        private $password;
        private $database;

        public function __construct(
                                    string $driver,
                                    string $host, 
                                    string $username, 
                                    string $password, 
                                    string $database
                                )
        {
                                $this->driver = $driver;
                                $this->host = $host;
                                $this->username = $username;
                                $this->password = $password;
                                $this->database = $database;
        }

        public function getDriver(): string {
            return $this->driver;
        }

        public function getHost(): string {
            return $this->host;
        }

        public function getUsername(): string {
            return $this->username;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function getDatabase(): string {
            return $this->database;
        }

    }
?>