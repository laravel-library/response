<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Illuminate\Contracts\Container\Container;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class HttpMessageConverterFactory implements Contacts\HttpMessageConverterBuilder
{
  private Container $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  #[Override]
  public function beforeBodyWrite(JsonResponse $jsonResponse): Response
  {
    $response = $this->container->get(Response::class);

    $response->setContent($jsonResponse->getContent());

    return $response;
  }

  #[Override]
  public function makeHttpMessageConverter(Response $response): HttpMessageConverter
  {
    return $this->container->make($this->getHttpMessageConverterClass($response));
  }

  private function getHttpMessageConverterClass(Response $response): string
  {
    return match (true) {
      $response->hasException()           => ThrowableHttpMessageConverter::class,
      $response->isArrayResponse()        => ArrayHttpMessageConverter::class,
      $response->isNormalStringResponse() => StringHttpMessageConverter::class,
      default                             => VoidHttpMessageConverter::class,
    };
  }
}
