<?php
    use App\Controllers\AppController;
    use App\Controllers\AdministratorDashboardController;
    use App\Controllers\AuthenticationController;
    use App\Controllers\UserDashboardController;
    use App\Controllers\ExceptionController;

    use App\Routing\Router;

    function handle(Router $router) {
        $router->add("|^auth/register/?$|", "GET", [AuthenticationController::class, "getCreate"]);
        $router->add("|^auth/register/?$|", "POST", [AuthenticationController::class, "postCreate"]);
        $router->add("|^auth/login/?$|", "GET", [AuthenticationController::class, "getLogin"]);
        $router->add("|^auth/login/?$|", "POST", [AuthenticationController::class, "postLogin"]);

            
        $router->add("|^user/dashboard/?$|", "GET", [UserDashboardController::class, "dashboard"]);
        $router->add("|^user/dashboard/?$|", "POST", [UserDashboardController::class, "vote"]);
        $router->add("|^user/dashboard/vote/?$|", "GET", [UserDashboardController::class, "userVote"]);
        $router->add("|^user/dashboard/logout/?$|", "GET", [AuthenticationController::class, "logout"]);

       
        $router->add("|^admin/dashboard/?$|", "GET", [AdministratorDashboardController::class, "adminDashboard"]);
        $router->add("|^admin/dashboard/candidates/add/?$|", "GET", [AdministratorDashboardController::class, "getCreate"]);
        $router->add("|^admin/dashboard/candidates/add/?$|", "POST", [AdministratorDashboardController::class, "postCreate"]);
        $router->add("|^admin/dashboard/candidates/edit/([1-9][0-9]*)/?$|", "GET", [AdministratorDashboardController::class, "getEdit"]);
        $router->add("|^admin/dashboard/candidates/edit/([1-9][0-9]*)/?$|", "POST", [AdministratorDashboardController::class, "postEdit"]);
        $router->add("|^admin/dashboard/candidates/delete/([1-9][0-9]*)/?$|", "GET", [AdministratorDashboardController::class, "delete"]);
        $router->add("|^admin/dashboard/logout/?$|", "GET", [AuthenticationController::class, "logout"]);
        
        $router->add("|^ *$|", "GET", [AppController::class, "app"]);         
        $router->add("|[^ .*]|", "GET", [ExceptionController::class, "exceptionPage"]);
    }
?>