<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;

final readonly class ArrayHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(Response|\Illuminate\Http\Response $body): Responsable
    {
        return $this->factory->toResponse($body->original);
    }
}