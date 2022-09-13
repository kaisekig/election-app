<?php
    namespace App\Models;

    use App\Config\DatabaseConnection;

    abstract class Model {
        private $connection;

        public function __construct(DatabaseConnection &$connection)
        {
            $this->connection = $connection;
        }

        public function &getConnect(): DatabaseConnection {
            return $this->connection;
        }

        public function getAll(): ?array {
            $tableName = $this->getTableName();
            $prep = $this->connection->connect()->prepare(
                "SELECT * FROM " . $tableName . ";"
            );

            $result = $prep->execute();

            if (!$result) {
                return null;
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }
        
        public function getById(int $id) {
            $tableName = $this->getTableName();
            $prep = $this->connection->connect()->prepare(
                "SELECT * FROM " . $tableName . " WHERE " . $tableName . "_id = ?;"
            );

            $result = $prep->execute([$id]);

            if (!$result) {
                return null;
            }

            return $prep->fetch(\PDO::FETCH_OBJ);
        }

        public function getAllByFieldName(string $fieldName, $value) {
            $tableName = $this->getTableName();
            $prep = $this->connection->connect()->prepare(
                "SELECT * FROM " . $tableName . " WHERE " . $fieldName . " = ?;"
            );

            $result = $prep->execute([$value]);

            if (!$result) {
                return null;
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getByFieldName(string $fieldName, $value) {
            $tableName = $this->getTableName();
            $prep = $this->connection->connect()->prepare(
                "SELECT * FROM " . $tableName . " WHERE " . $fieldName . " = ?;"
            );

            $result = $prep->execute([$value]);

            if (!$result) {
                return null;
            }

            return $prep->fetch(\PDO::FETCH_OBJ);
        }

        public function add(array $data) {
            $tableName = $this->getTableName();

            $fields = implode(", ", array_keys($data));
            $questionMarks = str_repeat("?, ", count($data));
            $questionMarks = substr($questionMarks, 0, -2);

            $sql = "INSERT INTO {$tableName} ({$fields}) VALUES ({$questionMarks});";

            $prep = $this->connection->connect()->prepare($sql);
            
            $result = $prep->execute(array_values($data));

            if (!$result) {
                return null;
            }

            return $this->connection->connect()->lastInsertId();
        }

        public function edit(int $id, array $data)  { 
            $tableName = $this->getTableName();
            $editList = [];
            $values = [];
            foreach ($data as $name => $value) {
                $editList[] = "{$name} = ?";
                $values[] = $value;
            }
            $editString = implode(", ", $editList);

            $values[] = $id;
            $sql = "UPDATE {$tableName} SET {$editString} WHERE {$tableName}_id = ?;";
            $prep = $this->connection->connect()->prepare($sql);
            
            try {
                return $prep->execute($values);
            } catch (\PDOException $except) {
                return null;
            }
        }

        public function delete(int $id) {
            $tableName = $this->getTableName();

            $sql = "DELETE FROM " . $tableName . " WHERE " . $tableName . "_id = ?;";
            $prep = $this->getConnect()->connect()->prepare($sql);

            # In case of foreign key constraint
            try {
                return $prep->execute([$id]);
            } catch (\PDOException $except) {
                return $except->getMessage();
            }
        }
        
        protected function getTableName(): string {
            $fullClassName = static::class;

            $matches = [];
            preg_match("|^.*\\\((?:[A-Z][a-z]+)+)Model$|", $fullClassName, $matches);
            $className = $matches[1] ?? "";
            $underscoredClassName = preg_replace("|[A-Z]|", "_$0", $className);
            $lowerUnderscoredClassName = strtolower($underscoredClassName);

            return substr($lowerUnderscoredClassName, 1);
        }
    }
?>