<?php
    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Models\CandidateModel;

    class AppController extends Controller {
        public function app() {
            $candidateModel = new CandidateModel($this->getConnect());
            $candidates     = $candidateModel->getAllCandidatesAscendingByNumber(); 

            $this->view("./views", "/app/index.html", ["candidates" => $candidates]);
        }
    }
?>