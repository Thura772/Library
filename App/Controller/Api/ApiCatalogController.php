<?php

namespace App\Controller\Api;

use App\Service\CatalogService;
use Exception;

class ApiCatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        try {
            $data = $this->catalogService->getCatalogPage($_GET);

            http_response_code(200);

            echo json_encode([
                'success' => true,
                'message' => 'Catalog fetched successfully',
                'data' => $data
            ]);
        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
