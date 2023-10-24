<?php

declare(strict_types=1);

namespace Elephant\Response\Middleware;

use Closure;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Elephant\Response\Converter\StringHttpMessageConverter;
use Elephant\Response\Converter\ThrowableHttpMessageConverter;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class FormatResponseBodyAdvice
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(Request $request, Closure $next): JsonResponse
    {

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $responder = $this->beforeBodyWrite($request, $response);

        return new JsonResponse($responder->toResponse());
    }

    private function beforeBodyWrite(Request $request, Response|\Illuminate\Http\Response $response): Responsable
    {
        return $this->prepareHttpMessageConverter($response)
            ->writeValueAsJsonResponse($request, $response)
            ->toResponse();
    }

    private function prepareHttpMessageConverter(\Illuminate\Http\Response $response): HttpMessageConverter
    {
        return is_null($response->exception)
            ? $this->container->make(StringHttpMessageConverter::class)
            : $this->container->make(ThrowableHttpMessageConverter::class);
    }
}