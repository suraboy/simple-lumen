<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\MessageBag;

/**
 * Class ValidationException
 * @package App\Exceptions
 */
class ValidationException extends \Exception
{
    /**
     * @var array
     */
    private $messages;

    /**
     * ValidationException constructor.
     * @param MessageBag $messageBag
     */
    public function __construct(MessageBag $messageBag)
    {
        $this->messages = $messageBag->toArray();
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->messages;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return 422;
    }
}
