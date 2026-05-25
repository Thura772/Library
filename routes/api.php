<?php

use App\Controller\Api\ApiCatalogController;
use App\Controller\Api\ApiDetailsController;
use App\Controller\Api\ApiSuggestController;

use App\Service\CatalogService;
use App\Service\FormatService;

/*
|----------------------------------------
| SERVICES (SIMPLE CONTAINER STYLE)
|----------------------------------------
*/

$catalogService = new CatalogService();
$formatService  = new FormatService();

/*
|----------------------------------------
| API ROUTER FUNCTION
|----------------------------------------
*/
function handleApi(string $page): void
{
    global $catalogService, $formatService;

    switch ($page) {

        case 'api/catalog':
            (new ApiCatalogController($catalogService))->index();
            break;

        case 'api/details':
            (new ApiDetailsController($catalogService))->show();
            break;

        case 'api/suggest':
            (new ApiSuggestController($formatService))->submit();
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'API route not found'
            ]);
    }
}
