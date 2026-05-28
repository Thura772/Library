<?php

namespace App\Controller;

use App\Service\FormatService;
use App\Controller\BaseController;
use PHPMailer\PHPMailer\PHPMailer;

class SuggestController extends BaseController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function index(): void
    {
        $data = [
            'pageTitle'     => 'Suggest a media item',
            'section'       => 'suggest',
            'hideSearch'    => true,
            'name'          => null,
            'email'         => null,
            'category'      => null,
            'title'         => null,
            'format'        => null,
            'genre'         => null,
            'year'          => null,
            'details'       => null,
            'error_message' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->handleForm();
            $data = array_merge($data, $formData);
        }

        $data['categories'] = $this->formatService->category_drop_down();
        $data['formats']    = $this->formatService->format_array();
        $data['genres']     = $this->formatService->genres_array();

        $this->view('suggest', $data);
    }

    private function handleForm(): array
    {
        $data = [
            'name'     => trim($_POST['name'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'title'    => trim($_POST['title'] ?? ''),
            'format'   => trim($_POST['format'] ?? ''),
            'genre'    => trim($_POST['genre'] ?? ''),
            'year'     => trim($_POST['year'] ?? ''),
            'details'  => trim($_POST['details'] ?? ''),
            'error_message' => null
        ];

        // Required validation
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {
            $data['error_message'] = "Please fill required fields";
            return $data;
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['error_message'] = "Invalid email address";
            return $data;
        }

        // Email body
        $email_body  = "Name: {$data['name']}\n";
        $email_body .= "Email: {$data['email']}\n\n";
        $email_body .= "Category: {$data['category']}\n";
        $email_body .= "Title: {$data['title']}\n";
        $email_body .= "Format: {$data['format']}\n";
        $email_body .= "Genre: {$data['genre']}\n";
        $email_body .= "Year: {$data['year']}\n";
        $email_body .= "Details:\n{$data['details']}\n";

        // PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            // ✅ MOST STABLE CONFIG FOR GMAIL (XAMPP FRIENDLY)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // IMPORTANT for localhost issues
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Optional debug (enable if error happens)
            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = 'html';

            // Sender / Receiver
            $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
            $mail->addReplyTo($data['email'], $data['name']);
            $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

            // Content
            $mail->Subject = 'Library Suggestion from: ' . $data['name'];
            $mail->Body = $email_body;

            $mail->send();

            header("Location: index.php?page=suggest&status=thanks");
            exit;

        } catch (\Exception $e) {
            $data['error_message'] = "Mailer Error: " . $mail->ErrorInfo;
        }

        return $data;
    }
}