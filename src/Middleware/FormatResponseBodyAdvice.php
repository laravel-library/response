<?php

declare(strict_types=1);

namespace Elephant\Response\Middleware;

use Closure;
use Elephant\Response\Converter\Contacts\HttpMessageConverterBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class FormatResponseBodyAdvice
{
    protected HttpMessageConverterBuilder $httpMessageConverterBuilder;

    public function __construct(HttpMessageConverterBuilder $builder)
    {
        $this->httpMessageConverterBuilder = $builder;
    }

    public function handle(Request $request, Closure $next): JsonResponse
    {

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response = $this->httpMessageConverterBuilder->beforeBodyWrite($response);
        }

        $responder = $this->httpMessageConverterBuilder
            ->makeHttpMessageConverter($response)
            ->writeValueAsJsonResponse($request, $response);

        return new JsonResponse($responder->toResponse());
    }
}