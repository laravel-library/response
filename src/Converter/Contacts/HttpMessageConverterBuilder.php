<?php

namespace Elephant\Response\Converter\Contacts;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface HttpMessageConverterBuilder
{
  public function makeHttpMessageConverter(Response $response): HttpMessageConverter;

  public function beforeBodyWrite(JsonResponse $jsonResponse): Response;
}
