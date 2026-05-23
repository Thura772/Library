<?php

/**
 * Handles displaying detailed information
 * for a single catalog item.
 */

require_once BASE_PATH . '/Controller/BaseController.php';

class DetailsController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(
        CatalogService $catalogService
    ) {
        $this->catalogService = $catalogService;
    }

    /**
     * Show item details page
     */
    public function show(): void
    {
        
        // Validate item ID
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        // Redirect if invalid
        if (!$id) {
            $this->redirect(
                BASE_URL . '/Public/index.php?page=catalog'
            );
        }

        // Get item
        $item = $this->catalogService
            ->single_item_array($id);

        // Redirect if not found
        if (empty($item)) {
            $this->redirect(
                BASE_URL . '/Public/index.php?page=catalog'
            );
        }

        // Render view
        $this->view('details', [
            'pageTitle' => $item['title'],
            'section'   => $item['category'],
            'item'      => $item
        ]);
    }
}