<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(
        string $message = 'Page not found'
    ) {
        parent::__construct($message);
    }
}
