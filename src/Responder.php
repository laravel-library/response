<?php

declare(strict_types=1);

namespace Elephant\Response;

use JetBrains\PhpStorm\ArrayShape;
use Override;

trait  Responder
{

    readonly mixed $data;

    readonly int $code;

    public function __construct(mixed $data, int $code)
    {
        $this->data = $data;
        $this->code = $code;
    }

    #[Override]
    #[ArrayShape(['msg' => 'string', 'code' => 'integer', 'data' => 'mixed'])]
    public function toResponse(): array
    {
        $response = ['msg' => $this->message(), 'code' => $this->code(), 'data' => null];

        if (is_string($this->data)) {
            $response['msg'] = $this->data;
        } elseif (is_array($this->data)) {
            $response['data'] = $this->data;
        }

        if ($this->code > 0) {
            $response['code'] = $this->code;
        }

        return $response;
    }

}
