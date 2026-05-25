<?php

define('BASE_PATH', realpath(__DIR__ . '/..'));
define('BASE_URL', '/Library');

require_once __DIR__ . '/../vendor/autoload.php';
require_once BASE_PATH . '/view/ItemView.php';
/*
|----------------------------------------
| LOAD ROUTES
|----------------------------------------
*/
require_once BASE_PATH . '/routes/api.php';
require_once BASE_PATH . '/routes/web.php';

/*
|----------------------------------------
| REQUEST DISPATCH
|----------------------------------------
*/
$page = $_GET['page'] ?? 'catalog';

/*
|----------------------------------------
| ROUTE HANDLING
|----------------------------------------
*/
if (str_starts_with($page, 'api/')) {
   handleApi($page);
   exit;
}

handleWeb($page);
