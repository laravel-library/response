<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Responsable;
use Illuminate\Http\Response;

final readonly class ThrowableHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(\Symfony\Component\HttpFoundation\Response|Response $body): Responsable
    {

        $status = $body->exception->getCode() === 0 ?
            $body->getStatusCode()
            : $body;

        return $this->factory->toResponse($body->exception->getMessage(), $status);
    }
}