<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class AbstractHttpMessageConverter implements HttpMessageConverter
{
    protected Factory $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    #[Override]
    final public function writeValueAsJsonResponse(Request $request, Response $body): Responsable
    {
        $this->factory->setRequest($request);

        return $this->write($body);
    }

    abstract protected function write(Response $body): Responsable;
}