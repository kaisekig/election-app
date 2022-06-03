<?php
    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Models\UserModel;

    class AuthenticationController extends Controller {
        public function getCreate() {
            $this->view("./views", "/auth/register.html", []);
        }

        public function postCreate() {
            $username       = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password       = filter_input(INPUT_POST, "password1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $secondPassword = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $identifier     = filter_input(INPUT_POST, "identifier", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $userModel = new UserModel($this->getConnect());
            $user = $userModel->getByFieldName("username", $username);

            if ($user) {
                $this->view("./views", "/auth/register.html", [
                    "message" => "Username you have entered already exists!"
                ]);
                return;
            }

            if ($password !== $secondPassword) {
                $this->view("./views", "/auth/register.html", [
                    "message" => "Password you have entered doesn't match!"
                ]);
                return;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $userId = $userModel->add(
                [
                    "username"   => $username,
                    "password"   => $passwordHash,
                    "identifier" => $identifier
                ]
            );

            if (!$userId) {
                $this->view("./views", "/exception/500.html", [
                    "message" => "Something went wrong! Please try again later."
                ]);
                return;
            }

            $this->redirect("/auth/login");

        }

        public function getLogin() {
            $this->view("./views", "/auth/login.html", []);
        }

        public function postLogin() {
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $userModel = new UserModel($this->getConnect());
            $user = $userModel->getByFieldName("username", $username);

            if (!$user) {
                $this->view("./views", "/auth/login.html", [
                    "message" => "Username or password you have entered doesn't match!"
                ]);
                return;
                
            }

            if ($user->is_active !== 1) {
                $this->view("./views", "/auth/login.html", [
                    "message" => "Account invalidated!"
                ]);
                return;
            }

            if (!password_verify($password, $user->password)) {
                $this->view("./views", "/auth/login.html", [
                    "message" => "Username or password you have entered doesn't match!"
                ]);
                return;
            }
            
            $this->getSession()->set("user_id", $user->user_id);
            $this->getSession()->set("role", $user->role);
            $this->getSession()->save();

            $this->getSession()->regenerate();

            if ($this->getSession()->get("role") === "user") {
                $this->redirect("/user/dashboard");
            }

            if ($this->getSession()->get("role") === "admin") {
                $this->redirect("/admin/dashboard");
            }
        }

        public function logout() {
            $this->getSession()->delete("role");
            $this->getSession()->delete("user_id");
            $this->getSession()->save();

            $this->redirect("/");
        }
    }
?>

