<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

use Elephant\Response\Converter\Contacts\HttpMessageConverter;
use Exception;
use Illuminate\Contracts\Container\Container;
use Override;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class HttpMessageConverterFactory implements Contacts\HttpMessageConverterBuilder
{

	public function __construct(private Container $container) {}

	#[Override]
	public function beforeBodyWrite(JsonResponse $symfonyResponse): Response
	{

		if (property_exists($symfonyResponse,'exception') && $symfonyResponse->exception instanceof Exception) {
			return $symfonyResponse;
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
