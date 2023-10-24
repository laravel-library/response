<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;

readonly class VoidHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(Response $body): Responsable
    {
        return $this->factory->toResponse();
    }
}