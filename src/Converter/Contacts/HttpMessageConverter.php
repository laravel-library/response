<?php

namespace Elephant\Response\Converter\Contacts;

use Elephant\Response\Contacts\Responsable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HttpMessageConverter
{
    public function writeValueAsJsonResponse(Response $body): Responsable;
}