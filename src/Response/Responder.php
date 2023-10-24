<?php

namespace Elephant\Response\Response;

use JetBrains\PhpStorm\ArrayShape;

trait  Responder
{

    readonly mixed $data;

    readonly int $code;

    public function __construct(mixed $data, int $code)
    {
        $this->data = $data;
        $this->code = $code;
    }

    #[ArrayShape(['message' => 'string', 'code' => 'integer', 'data' => 'mixed'])]
    public function toResponse(): array
    {
        $response = ['message' => $this->message(), 'code' => $this->code(), 'data' => null];

        if (is_string($this->data)) {
            $response['message'] = $this->data;
        } elseif (!is_null($this->data)) {
            $response['data'] = $this->data;
        }

        if ($this->code > 0) {
            $response['code'] = $this->code;
        }

        return $response;
    }
}