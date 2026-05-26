<?php

use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;
use App\Controller\AuthController;

use App\Service\CatalogService;
use App\Service\FormatService;
use App\Service\AuthService;
use App\inc\Database;
use App\Repository\UserRepository;

/*
|----------------------------------------
| SERVICES
|----------------------------------------
*/

$catalogService = new CatalogService();
$formatService  = new FormatService();

/*
| DB + AUTH
|----------------------------------------
*/
$db = Database::getConnection();

$userRepository = new UserRepository($db);
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);

/*
|----------------------------------------
| WEB ROUTER FUNCTION
|----------------------------------------
*/
function handleWeb(string $page): void
{
    global $catalogService, $formatService, $authController;

    /*
    | AUTH ROUTE
    */
   /*
| AUTH ROUTES
*/
if ($page === 'register') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } else {
        $authController->showRegisterForm();
    }

    return;
}

if ($page === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->login();
    } else {
        $authController->showLoginForm();
    }

    return;
}

if ($page === 'logout') {

    $authController->logout();

    return;
}

    /*
    | NORMAL ROUTES
    */
    switch ($page) {

        case 'details':
            (new DetailsController($catalogService))->show();
            break;

        case 'suggest':
            (new SuggestController($formatService))->index();
            break;
        case 'catalog':
            (new CatalogController($catalogService))->index();
            break;
        default:
            (new CatalogController($catalogService))->home();
            break;
    }
}