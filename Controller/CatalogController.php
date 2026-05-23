<?php

require_once BASE_PATH . '/Controller/BaseController.php';

class CatalogController extends BaseController
{
    private readonly CatalogService $catalogService;

    public function __construct(
        CatalogService $catalogService
    ) {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        $random = $this->catalogService
            ->random_catalog_array();

        $this->view('home', [
            'pageTitle' => 'Personal Media Library',
            'section'   => 'catalog',
            'random'    => $random
        ]);
    }
}