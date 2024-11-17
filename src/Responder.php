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
        $this->prepareResponse($data, $code);
    }

    #[ArrayShape(['msg' => 'string', 'code' => 'integer', 'data' => 'mixed'])]
    #[Override]
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

    private function prepareResponse(mixed $data, int $code): void
    {
        if ($this->hasCustomCode($data)) {
            $code = $data['code'];
            unset($data['code']);
        }

        $this->data = $data;
        $this->code = $code;
    }

    private function hasCustomCode(mixed $data): bool
    {
        return is_array($data) && isset($data['code']);
    }
}
