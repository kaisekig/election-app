<?php
    namespace App\Models;

    use App\Models\Model;

    class CandidateModel extends Model {
        public function getCandidateByFullName(string $forename, string $surname) {
            $tableName = $this->getTableName();

            $sql = "SELECT * FROM {$tableName} where forename = ? AND surname = ?;";
            $prep = $this->getConnect()->connect()->prepare($sql);
            $result = $prep->execute([$forename, $surname]);

            if ($result) { 
                return null;
            }

            return $prep->fetch(\PDO::FETCH_OBJ);
        }

        public function getAllCandidatesAscendingByNumber() {
            $tableName = $this->getTableName();

            $sql = "SELECT * FROM {$tableName} ORDER BY `number` ASC;";
            $prep = $this->getConnect()->connect()->prepare($sql);
            $result = $prep->execute();

            if (!$result) {
                return null;
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getCandidateVotes() {
            $tableName = $this->getTableName();

            $sql  = "SELECT * FROM {$tableName} INNER JOIN vote ON candidate.candidate_id = vote.candidate_id;";
            $prep = $this->getConnect()->connect()->prepare($sql);
            $result = $prep->execute();
            
            if (!$result) {
                return null;
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }
    }
?>