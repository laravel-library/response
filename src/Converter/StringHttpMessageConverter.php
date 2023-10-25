<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;

final readonly class StringHttpMessageConverter extends AbstractHttpMessageConverter
{

    protected function write(Response $body): Responsable
    {
        $content = $body->getContent();

        $responseData = $this->validateJsonLegality($content) ? json_decode($content) : $content;

        return $this->factory->toResponse($responseData);
    }

    private function validateJsonLegality(string $content): bool
    {
        json_decode($content);

        return json_last_error() === JSON_ERROR_NONE;
    }
}