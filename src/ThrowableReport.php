<?php

namespace Elephant\Response;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

readonly class ThrowableReport implements Reportable
{
	public function report(JsonResponse|Throwable $response): array
	{
		$throwable = $this->wrap($response);

		return [
			'msg'       => $throwable->getMessage(),
			'code'      => $throwable->getCode() <= 0 ? 500 : $throwable->getCode(),
			'exception' => is_null($throwable->getPrevious())
				? Exception::class
				: get_class($throwable->getPrevious()),
			'file'      => $throwable->getFile(),
			'line'      => $throwable->getLine(),
			'trace'     => Collection::make($throwable->getTrace())
				->map(fn(array $trace): array => Arr::except($trace, ['args'])),
		];
	}

	protected function wrap(Throwable $throwable): Throwable
	{

		if ($throwable instanceof QueryException) {
			return new Exception($throwable->getMessage(), 500, $throwable);
		}

		$errors = match (true) {
			$throwable instanceof MissingAbilityException                                               => [$throwable->getMessage(), 403],
			$throwable instanceof AuthenticationException                                               => [$throwable->getMessage(), 401],
			$throwable instanceof MethodNotAllowedException                                             => [$throwable->getMessage(), 405],
			$throwable instanceof ValidationException                                                   => [$throwable->validator->errors()->first(), 422],
			$throwable instanceof HttpException                                                         => [$throwable->getMessage(), $throwable->getStatusCode()],
			$throwable instanceof ModelNotFoundException || $throwable instanceof NotFoundHttpException => [$throwable->getMessage(), 404],
			default                                                                                     => [$throwable->getMessage(), ($throwable->getCode() >= 500 ? $throwable->getCode() : 500)]
		};

		return new Exception(current($errors), next($errors));
	}
}