<?php

namespace Elephant\Response\Contacts;

use Symfony\Component\HttpFoundation\Request;

interface Factory extends Responsable
{
    public function toResponse(mixed $data = null, int $code = 0): Response;

    public function setRequest(Request $request): Responsable;
}