<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once BASE_PATH . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/Contract/BaseRepositoryInterface.php';
require_once BASE_PATH . '/Repository/BaseRepository.php';
require_once BASE_PATH . '/Contract/CatalogRepositoryInterface.php';
require_once BASE_PATH . '/Contract/FormatRepositoryInterface.php';
require_once BASE_PATH . '/Repository/CatalogRepository.php';
require_once BASE_PATH . '/Repository/FormatRepository.php';
require_once BASE_PATH . '/Service/CatalogService.php';
require_once BASE_PATH . '/Service/FormatService.php';
require_once BASE_PATH . '/view/ItemView.php';
require_once BASE_PATH . '/Controller/Api/ApiCatalogController.php';
require_once BASE_PATH . '/Controller/Api/ApiDetailsController.php';
require_once BASE_PATH . '/Controller/Api/ApiSuggestController.php';
require_once BASE_PATH . '/Controller/CatalogController.php';
require_once BASE_PATH . '/Controller/DetailsController.php';
require_once BASE_PATH . '/Controller/SuggestController.php';

require_once BASE_PATH . '/Repository/UserRepository.php';
require_once BASE_PATH . '/Service/AuthService.php';
require_once BASE_PATH . '/Controller/AuthController.php';



$catalogService = new CatalogService();
$formatService = new FormatService();

$db = Database::getConnection();

$userRepository = new UserRepository($db); // Changed from $pdoInstance to $pdo
$authService    = new AuthService($userRepository);
$authController = new AuthController($authService);

/* =========================
   ROUTING
========================= */

$page = $_GET['page']?? "register";

/* =========================
   API ROUTES (IMPORTANT FIRST)
========================= */
if (str_starts_with($page, 'api/')) {

    switch ($page) {

        case 'api/catalog':
            $controller = new ApiCatalogController($catalogService);
            $controller->index();
            exit;

        case 'api/details':
            $controller = new ApiDetailsController($catalogService);
            $controller->show();
            exit;

        case 'api/suggest':
            $controller = new ApiSuggestController($formatService);
            $controller->submit();
            exit;

        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'API endpoint not found'
            ]);
            exit;
    }
}

// Inside your routing logic in Public/index.php

if ($page === 'register') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } else {
        $authController->showRegisterForm();
    }
    exit;
}
/* =========================
   NORMAL MVC ROUTES
========================= */

switch ($page) {

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;

    default:
        $controller = new CatalogController($catalogService);
        $controller->home();
        break;
}