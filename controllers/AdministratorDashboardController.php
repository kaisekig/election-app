<?php
    namespace App\Controllers;
    
    use App\Core\RoleController;
    use App\Models\CandidateModel;

    class AdministratorDashboardController extends RoleController {
        public function adminDashboard() {
            $this->allowToRoleAdmin();

            $candidateModel = new CandidateModel($this->getConnect());
            $candidates = $candidateModel->getAll();

            $this->view("./views", "/admin/dashboard.html", [
                "candidates" => $candidates
            ]);
        }

        public function getCreate() {
            $this->allowToRoleAdmin();

            $this->view("./views", "/admin/create.html", []);
        }

        public function postCreate() {
            $this->allowToRoleAdmin();

            $name            = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $surname         = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $politicalParty  = filter_input(INPUT_POST, "party", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $candidateNumber = filter_input(INPUT_POST, "number", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $candidateModel = new CandidateModel($this->getConnect());
            $candidateId = $candidateModel->add(
                [
                    "forename"    => $name,
                    "surname" => $surname,
                    "party"   => $politicalParty,
                    "number"  => $candidateNumber
                ]
            );

            if (!$candidateId) {
                $this->view("./views", "/exception/500.html", [
                    "message" => "Couldn't create candidate!"
                ]);
                return;
            }

            $this->redirect("/admin/dashboard", 303);
        }

        public function getEdit(int $id) {
            $this->allowToRoleAdmin(); 

            $candidateModel = new CandidateModel($this->getConnect());
            $candidate = $candidateModel->getById($id);

            $this->view("./views", "/admin/getEdit.html", ["candidate" => $candidate]);
        }

        public function postEdit(int $id) {
            $this->allowToRoleAdmin();

            $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $number = filter_input(INPUT_POST, "number", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $party = filter_input(INPUT_POST, "party", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $candidateModel = new CandidateModel($this->getConnect());
            $response = $candidateModel->edit($id, [
                "forename" => $name,
                "surname" => $surname, 
                "party" => $party, 
                "number" => $number
            ]);
            
            if (!$response) {
                $this->view("./views", "/exception/500.html", [
                    "message" => "Couldn't update candidate!"
                ]);
                return;
            }

            $this->redirect("/admin/dashboard");
        }

        public function delete(int $id) {
            $this->allowToRoleAdmin();

            $candidateModel = new CandidateModel($this->getConnect());
            $response = $candidateModel->delete($id);

            if (!$response) {
                $this->view("./views", "/exception/500.html", [
                    "message" => "Couldn't delete candidate!"
                ]);
                return;
            }

            $this->redirect("/admin/dashboard");
        }
    }
?>