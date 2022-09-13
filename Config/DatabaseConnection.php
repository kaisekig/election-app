<?php
    namespace App\Config;

    class DatabaseConnection {
        private $instance;
        private $configuration;

        public function __construct(DatabaseConfiguration $configuration)
        {
            $this->configuration = $configuration;
        }

        public function connect(): \PDO {
            if (!isset($this->instance)) {
                $this->instance = new \PDO(
                    "{$this->configuration->getDriver()}:host={$this->configuration->getHost()};dbname={$this->configuration->getDatabase()};",
                    $this->configuration->getUsername(),
                    $this->configuration->getPassword()
                );
            }

            return $this->instance;
        }
    }
?>