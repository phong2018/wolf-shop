<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $messageCode = null;

    /**
     * Set the message code
     *
     * @return self
     */
    public function setMessageCode(string $code)
    {
        $this->messageCode = $code;

        return $this;
    }

    /**
     * Get the message code
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Create new exception instance with code
     *
     * @return self
     */
    public static function code($code, $args = [], $statusCode = 400)
    {
        return (new static(__('messages.' . $code, $args), $statusCode))->setMessageCode($code); // @phpstan-ignore-line
    }
}
