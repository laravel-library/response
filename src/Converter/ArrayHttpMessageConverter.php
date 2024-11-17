<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Responsable;
use Override;
use Symfony\Component\HttpFoundation\Response;

final readonly class ArrayHttpMessageConverter extends AbstractHttpMessageConverter
{

  #[Override]
  protected function write(Response $body): Responsable
  {
    return $this->factory->toResponse(json_decode($body->getContent(), true));
  }
}
