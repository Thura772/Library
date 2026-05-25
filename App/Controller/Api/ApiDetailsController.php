<?php

namespace App\Controller\Api;

use App\Service\CatalogService;
use Exception;

class ApiDetailsController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        header('Content-Type: application/json');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid ID'
            ]);
            return;
        }

        try {
            $item = $this->catalogService->getById($id);

            if (!$item) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $item
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
