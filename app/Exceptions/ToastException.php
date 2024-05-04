<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ToastException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $type
     * @param string $title
     * @param string $description
     * @param string $position
     * @param int $timeout
     * @param string $redirectTo
     * @return void
     */
    public function __construct(
        public string $type,
        public string $title,
        public ?string $description = null,
        public string $position = 'toast-top toast-end',
        public int $timeout = 3000,
        public ?string $redirectTo = null
    ) {
        parent::__construct("ToastException: $title");

        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->position = $position;
        $this->timeout = $timeout;
        $this->redirectTo = $redirectTo;
    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        // You can log the exception using Laravel's logging system
        Log::error("ToastException: {$this->title}");
    }
}
