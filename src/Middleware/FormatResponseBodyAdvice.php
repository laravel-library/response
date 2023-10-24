<?php

declare(strict_types=1);

namespace Elephant\Response\Middleware;

use Closure;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Converter\ArrayHttpMessageConverter;
use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Elephant\Response\Converter\StringHttpMessageConverter;
use Elephant\Response\Converter\ThrowableHttpMessageConverter;
use Elephant\Response\Converter\VoidHttpMessageConverter;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
            $response = $this->jsonResponseConvertToHttpResponse($response);
        }

        $responder = $this->beforeBodyWrite($response);

        return new JsonResponse($responder->toResponse());
    }

    private function jsonResponseConvertToHttpResponse(JsonResponse $jsonResponse): Response|\Illuminate\Http\Response
    {
        $response = $this->container->get(\Illuminate\Http\Response::class);

        $response->setContent($jsonResponse->original);

        return $response;
    }

    private function beforeBodyWrite(Response|\Illuminate\Http\Response $response): Responsable
    {
        return $this->prepareHttpMessageConverter($response)
            ->writeValueAsJsonResponse($response);
    }

    private function prepareHttpMessageConverter(\Illuminate\Http\Response $response): HttpMessageConverter
    {
        $isThrowable = is_string($response->original) && ($response->exception instanceof Throwable);

        $converter = match (true) {
            $isThrowable                   => ThrowableHttpMessageConverter::class,
            is_string($response->original) => StringHttpMessageConverter::class,
            !is_null($response->original)  => ArrayHttpMessageConverter::class,
            default                        => VoidHttpMessageConverter::class,
        };

        return $this->container->make($converter);
    }
}