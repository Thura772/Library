<?php

namespace App\Controller;

use App\Service\CatalogService;
use App\Controller\BaseController;

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
    public function index()
    {
        $pageTitle = "Full Catalog";
        $section = null;
        $search = null;

        $item_per_page = 8;

        // CATEGORY FILTER
        if (isset($_GET["cat"])) {

            if ($_GET["cat"] === "books") {
                $pageTitle = "Books";
                $section = "books";
            } elseif ($_GET["cat"] === "movies") {
                $pageTitle = "Movies";
                $section = "movies";
            } elseif ($_GET["cat"] === "music") {
                $pageTitle = "Music";
                $section = "music";
            }
        }

        // SEARCH
        if (isset($_GET["s"])) {
            $search = trim($_GET["s"]);
        }

        // PAGE
        $current_page = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
        if (!$current_page || $current_page < 1) {
            $current_page = 1;
        }

        // COUNT
        $totalItems = $this->catalogService
            ->get_catalog_count($section, $search);

        $total_pages = max(1, ceil($totalItems / $item_per_page));

        if ($current_page > $total_pages) {
            $current_page = $total_pages;
        }

        $offset = ($current_page - 1) * $item_per_page;

        // LOAD DATA
        if (!empty($search) && !empty($section)) {

            $catalog = $this->catalogService->search_catalog_array(
                $search,
                $section,
                $item_per_page,
                $offset
            );
        } elseif (!empty($search)) {

            $catalog = $this->catalogService->search_catalog_array(
                $search,
                null,
                $item_per_page,
                $offset
            );
        } elseif (!empty($section)) {

            $catalog = $this->catalogService->category_catalog_array(
                $section,
                $item_per_page,
                $offset
            );
        } else {

            $catalog = $this->catalogService->full_catalog_array(
                $item_per_page,
                $offset
            );
        }

        $totalPages = max(1, ceil($totalItems / $item_per_page));

        $this->view('catalog', [
            'catalog' => $catalog,
            'pageTitle' => $pageTitle,
            'section' => $section,
            'search' => $search,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $current_page
        ]);
    }
}
