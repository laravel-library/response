<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Response;
use Override;

final readonly class ArrayHttpMessageConverter extends AbstractHttpMessageConverter
{

  #[Override]
  protected function write(Response $body): Responsable
  {
    return $this->factory->toResponse(json_decode($body->getContent(), true));
  }
}
