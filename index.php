<?php
    require_once "vendor/autoload.php";
    require_once "routes.php";

    use App\Config\DatabaseConfiguration;
    use App\Config\DatabaseConnection;
    use App\Core\Kernel;
    use App\Routing\Router;
    use App\Core\Session\Session;
    use App\Controllers\AdministratorDashboardController;
    use App\Controllers\AppController;
    use App\Controllers\AuthenticationController;
    use App\Controllers\ExceptionController;
    use App\Controllers\UserDashboardController;
    use App\Controllers\VoteController;
    use App\Core\Fingerprint\BasicFingerprintProvider;
    use App\Core\Session\FileSessionStorage;

    $databaseConfiguration = new DatabaseConfiguration("mysql", "localhost", "root", "root", "election");
    $databaseConnection = new DatabaseConnection($databaseConfiguration);

    $kernel = new Kernel($databaseConnection, [
        AppController::class,
        AuthenticationController::class,
        AdministratorDashboardController::class,
        UserDashboardController::class,
        VoteController::class,
        ExceptionController::class
    ]);

    $router = new Router();
    $router->callHandle($router);
    $router->dispatch();
    $route = $router->getRoute();
    $args  = $router->getArguments();

    $fingerprintProvider = new BasicFingerprintProvider($_SERVER);
    $sessionStorage = new FileSessionStorage("./sessions/");
    $session = new Session($sessionStorage, 3600);

    $session->setFingerprintProvider($fingerprintProvider);
    $kernel->setSession($session);
    $handler = $kernel->rebuild($route->getHandler());

    call_user_func_array($handler, $args);
?>