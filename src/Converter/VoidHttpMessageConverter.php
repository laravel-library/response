<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;

final readonly class VoidHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(Response $body): Responsable
    {
        return $this->factory->toResponse();
    }
}