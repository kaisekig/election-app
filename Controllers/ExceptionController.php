<?php
    namespace App\Controllers;

    use App\Controllers\Controller;

    class ExceptionController extends Controller {
        public function exceptionPage() {
            $this->view("./views", "exception/404.html");
        }
    }
?>