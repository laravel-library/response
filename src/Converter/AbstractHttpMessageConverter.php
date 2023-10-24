<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class AbstractHttpMessageConverter implements HttpMessageConverter
{
    protected Factory $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    final public function writeValueAsJsonResponse(Response $body): Responsable
    {
        return $this->write($body);
    }

    abstract protected function write(Response $body): Responsable;
}