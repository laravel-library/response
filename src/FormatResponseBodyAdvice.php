<?php

declare(strict_types=1);

namespace Elephant\Response\Middleware;

use Closure;
use Elephant\Response\Converter\Contacts\HttpMessageConverterBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class FormatResponseBodyAdvice
{
    public function __construct(private HttpMessageConverterBuilder $builder) {}

    public function handle(Request $request, Closure $next): JsonResponse
    {

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response = $this->builder->beforeBodyWrite($response);
        }

        return new JsonResponse(
            $this->builder
                ->makeHttpMessageConverter($response)
                ->writeValueAsJsonResponse($request, $response)
                ->toResponse()
        );
    }
}
