<?php


namespace App;


class ErrorModel
{
    public string $message;

    /**
     * ErrorModel constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

}
