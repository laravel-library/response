<?php

namespace Elephant\Response\Converter\Contacts;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

interface HttpMessageConverterBuilder
{
    public function makeHttpMessageConverter(Response $response): HttpMessageConverter;

    public function beforeBodyWrite(JsonResponse $jsonResponse): \Symfony\Component\HttpFoundation\Response|Response;
}