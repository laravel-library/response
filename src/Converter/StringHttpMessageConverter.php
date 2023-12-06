<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;
use Override;

final readonly class StringHttpMessageConverter extends AbstractHttpMessageConverter
{

    #[Override]
    protected function write(Response $body): Responsable
    {
        $content = $body->getContent();

        $responseData = json_validate($content) ? json_decode($content) : $content;

        return $this->factory->toResponse($responseData);
    }
}