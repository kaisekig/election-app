<?php
    namespace App\Controllers;

    use App\Core\RoleController;
    use App\Models\CandidateModel;
    use App\Models\UserModel;
    use App\Models\VoteModel;

    class UserDashboardController extends RoleController {
        public function dashboard() {
            $this->allowToRoleUser();

            $candidateModel = new CandidateModel($this->getConnect());
            $candidates = $candidateModel->getAllCandidatesAscendingByNumber();

            $this->view("./views", "/user/dashboard.html", [
                "candidates" => $candidates
            ]);
        }

        public function vote() {
            $this->allowToRoleUser();

            $userId = $this->getSession()->get("user_id");
            $userModel = new UserModel($this->getConnect());
            $user = $userModel->getById($userId);

            if ($user->is_voted !== 0) {
                $this->getSession()->delete("role");
                $this->getSession()->save();
                
                $this->redirect("/");
            }

            $candidateId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $voteModel = new VoteModel($this->getConnect());
            $voteId = $voteModel->add([
                "candidate_id" => $candidateId
            ]);

            if (!$voteId) {
                $this->view("./views", "/exception/500.html", [
                    "message" => "Something went wrong! Couldn't vote."
                ]);
                return;
            }

            try {
                $userModel->edit($userId, [
                    "is_active" => 0,
                    "is_voted" => 1,
                    
                ]);
            } catch (\Exception $error){
                return $error->getMessage();
            }

            $this->redirect("/user/dashboard/vote");
        }

        public function userVote() {
            $this->allowToRoleUser();

            $userId = $this->getSession()->get("user_id");

            $userModel = new UserModel($this->getConnect());
            $user = $userModel->getById($userId);

            if ($user->is_voted !== 0) {
                $this->view("./views", "/user/vote.html", [
                    "message" => "You have voted!"
                ]);
                return;
            }

            $this->view("./views", "/user/vote.html", [
                "message" => "You Haven't voted yet!"
            ]);
            return;

        }
    }
?>