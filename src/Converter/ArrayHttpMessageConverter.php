<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;

readonly class ArrayHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(Response|\Illuminate\Http\Response $body): Responsable
    {
        return $this->factory->toResponse($body->original);
    }
}