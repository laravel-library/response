<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Response;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

final readonly class HttpMessageConverterFactory implements Contacts\HttpMessageConverterBuilder
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    #[Override]
    public function beforeBodyWrite(JsonResponse $jsonResponse): \Symfony\Component\HttpFoundation\Response|Response
    {
        $response = $this->container->get(Response::class);

        $response->setContent($jsonResponse->original);

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
            $this->responseIsException($response) => ThrowableHttpMessageConverter::class,
            is_string($response->original)        => StringHttpMessageConverter::class,
            !is_null($response->original)         => ArrayHttpMessageConverter::class,
            default                               => VoidHttpMessageConverter::class,
        };
    }

    private function responseIsException(Response $response): bool
    {
        return is_string($response->original) && ($response->exception instanceof Throwable);
    }
}