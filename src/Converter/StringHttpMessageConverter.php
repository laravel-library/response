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
    $content = !$this->isChinese($body) || $this->isBase64($body)
      ? new AnonymousValue($body->getContent())
      : $body->getContent();

    return $this->factory->toResponse($content);
  }

  public function isChinese(Response $response): bool
  {
    return preg_match('/[\x{4e00}-\x{9fa5}]/u', $response->getContent()) > 0;
  }

  public function isBase64(Response $response): bool
  {
    return preg_match('/^[A-Za-z0-9+\/=]+$/', $response->getContent()) && strlen($response->getContent()) % 4 === 0;
  }
}
