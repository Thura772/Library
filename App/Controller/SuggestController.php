<?php

namespace App\Controller;

use App\Service\FormatService;
use App\Controller\BaseController;

/**
 * Handles media suggestion requests.
 */


use PHPMailer\PHPMailer\PHPMailer;

class SuggestController extends BaseController
{
    private FormatService $formatService;

    public function __construct(
        FormatService $formatService
    ) {
        $this->formatService = $formatService;
    }

    /**
     * Show suggest page
     */
    public function index(): void
    {
        $data = [
            'pageTitle'    => 'Suggest a media item',
            'section'      => 'suggest',
            'hideSearch'   => true,
            'name'         => null,
            'email'        => null,
            'category'     => null,
            'title'        => null,
            'format'       => null,
            'genre'        => null,
            'year'         => null,
            'details'      => null,
            'error_message' => null
        ];

        // Handle form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $formData = $this->handleForm();

            $data = array_merge($data, $formData);
        }

        // Dropdown data
        $data['categories']
            = $this->formatService
            ->category_drop_down();

        $data['formats']
            = $this->formatService
            ->format_array();

        $data['genres']
            = $this->formatService
            ->genres_array();

        $this->view('suggest', $data);
    }

    /**
     * Handle form submission
     */
    private function handleForm(): array
    {
        $data = [
            'name' => trim(filter_input(INPUT_POST, "name")),
            'email' => trim(filter_input(INPUT_POST, "email")),
            'category' => trim(filter_input(INPUT_POST, "category")),
            'title' => trim(filter_input(INPUT_POST, "title")),
            'format' => trim(filter_input(INPUT_POST, "format")),
            'genre' => trim(filter_input(INPUT_POST, "genre")),
            'year' => trim(filter_input(INPUT_POST, "year")),
            'details' => trim(filter_input(INPUT_POST, "details")),
            'error_message' => null
        ];

        // Validation
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {

            $data['error_message']
                = 'Please fill required fields';

            return $data;
        }

        // Validate email
        if (
            !PHPMailer::validateAddress(
                $data['email']
            )
        ) {

            $data['error_message']
                = 'Invalid email address';

            return $data;
        }

        // Send email here...
        /* SEND EMAIL */

        // Build email message body
        $email_body = "Name: {$data['name']}\n";
        $email_body .= "Email: {$data['email']}\n\n";
        $email_body .= "Category: {$data['category']}\n";
        $email_body .= "Title: {$data['title']}\n";
        $email_body .= "Format: {$data['format']}\n";
        $email_body .= "Genre: {$data['genre']}\n";
        $email_body .= "Year: {$data['year']}\n";
        $email_body .= "Details:\n{$data['details']}\n";

        // Configure PHPMailer
        $mail = new PHPMailer(true);

        $mail->isSMTP();

        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;

        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        // Set sender and receiver
        $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
        $mail->addReplyTo($data['email'], $data['name']);
        $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

        // Set email content
        $mail->Subject = 'Library Suggestion from: ' . $data['name'];
        $mail->Body = $email_body;

        // Send email and redirect on success
        if ($mail->send()) {
            header("Location: index.php?page=suggest&status=thanks");
            exit;
        }

        // Return mail error if sending fails
        $data['error_message'] = 'Mailer Error: ' . $mail->ErrorInfo;


        return $data;
    }
}
