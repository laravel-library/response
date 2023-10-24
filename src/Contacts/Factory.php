<?php

namespace Elephant\Response\Contacts;

interface Factory extends Responsable
{
    public function toResponse(mixed $data = null, int $code = 0): Response;
}