<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Contacts\Responsable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Override;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

final readonly class ThrowableHttpMessageConverter extends AbstractHttpMessageConverter
{

	#[Override]
	protected function write(\Symfony\Component\HttpFoundation\Response|Response $body): Responsable
	{
		$throwable = $body->exception;

		if ($this->isNotFoundException($throwable)) {
			return $this->factory->toResponse($throwable->getMessage(), 404);
		}

		if ($throwable instanceof ValidationException) {
			return $this->factory->toResponse($throwable->validator->errors()->first(), 422);
		}

		if ($throwable instanceof MethodNotAllowedException) {
			return $this->factory->toResponse($throwable->getMessage(), 405);
		}

		if ($throwable instanceof AuthenticationException) {
			return $this->factory->toResponse($throwable->getMessage(), 401);
		}

		if ($throwable instanceof MissingAbilityException) {
			return $this->factory->toResponse($throwable->getMessage(), 403);
		}

		if ($throwable instanceof HttpException) {
			return $this->factory->toResponse($throwable->getMessage(), $throwable->getStatusCode());
		}

		if ($throwable instanceof QueryException) {
			$throwable = new \RuntimeException($throwable->getMessage(), 500, $throwable);
		}

		if ($this->isDevelopment() && $this->isBasicException($throwable)) {

			$statusCode = $throwable->getCode() >= 500 ? $throwable->getCode() : 500;

			return $this->factory->toResponse(code: $statusCode, throwable: $throwable);
		}

		return $this->factory->toResponse($throwable->getMessage(), $throwable->getCode());
	}

	private function isBasicException(\Throwable $throwable): bool
	{
		return $throwable->getCode() >= 500 || $throwable->getCode() === 0;
	}

	private function isNotFoundException(Throwable $e): bool
	{
		return $e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException;
	}

	private function isDevelopment(): bool
	{
		return $this->factory->app()->isLocal() && $this->factory->app()->get('config')->get('app.debug');
	}
}