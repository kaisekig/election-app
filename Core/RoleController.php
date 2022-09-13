<?php
    namespace App\Core;

    use App\Controllers\Controller;

    class RoleController extends Controller {
        public function allowToRoleUser(string $key = "role") {
            $this->checkRole($key);

            $role = $this->getSession()->get($key);
            if ($role !== "user") {
                $this->redirect("/{$role}/dashboard");
            }
        }

        public function allowToRoleAdmin(string $key = "role") {
            $this->checkRole($key);

            $role = $this->getSession()->get($key);
            if ($role !== "admin") {
                $this->redirect("/{$role}/dashboard");
            }
        }

        private function checkRole(string $key) {
            if (!$this->getSession()->has($key)) {
                $this->redirect("/");
            }
        }
    }
?>