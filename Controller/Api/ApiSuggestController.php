<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class ApiSuggestController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function submit(): void
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Method not allowed'
                ]);
                return;
            }

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'title' => trim($_POST['title'] ?? ''),
                'format' => trim($_POST['format'] ?? ''),
                'genre' => trim($_POST['genre'] ?? ''),
                'year' => trim($_POST['year'] ?? ''),
                'details' => trim($_POST['details'] ?? '')
            ];

            if (!$data['name'] || !$data['email'] || !$data['category'] || !$data['title']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Required fields missing'
                ]);
                return;
            }

            // (Optional) send email using PHPMailer here
            // keep same logic you already wrote

            echo json_encode([
                'success' => true,
                'message' => 'Suggestion submitted successfully',
                'data' => $data
            ]);

        } catch (Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}