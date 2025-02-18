<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Illuminate\Contracts\Container\Container;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

		if ($jsonResponse->exception instanceof Throwable) {
            return $jsonResponse;
        }

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
			$this->isNotSymfonyResponse($response)     => ThrowableHttpMessageConverter::class,
			json_validate($response->getContent())     => ArrayHttpMessageConverter::class,
			is_string($response->getContent())
			&& !empty($response->getContent())
			&& !json_validate($response->getContent()) => StringHttpMessageConverter::class,
			default                                    => VoidHttpMessageConverter::class,
		};
	}

	private function isNotSymfonyResponse(Response $response): bool
	{
		return get_class($response) !== Response::class && $response->exception instanceof Throwable;
	}
}
