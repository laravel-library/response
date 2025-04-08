<?php

namespace Elephant\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

interface Reportable
{
    public function report(JsonResponse|Throwable $response): array;
}