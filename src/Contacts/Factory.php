<?php

namespace Elephant\Response\Contacts;

use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

interface Factory extends Responsable
{
    public function toResponse(mixed $data = null, int $code = 0, Throwable $throwable = null): Response;

    public function setRequest(Request $request): void;

    public function app(): Container;
}