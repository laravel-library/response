<?php

namespace Elephant\Response\Converter;

use Elephant\Response\Response\Contacts\Responsable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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

        if ($throwable instanceof HttpException) {
            return $this->factory->toResponse($throwable->getMessage(), $throwable->getStatusCode());
        }

        return $this->isLocal() && $this->isEnabledDebug()
            ? $this->factory->toResponse(throwable: $throwable)
            : $this->factory->toResponse(
                $throwable->getMessage(),
                $throwable->getCode() <= 0 ? 500 : $throwable->getCode()
            );
    }

    private function isNotFoundException(Throwable $e): bool
    {
        return $e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException;
    }

    private function isLocal(): bool
    {
        return $this->factory->app()->get('env');
    }

    private function isEnabledDebug(): bool
    {
        return $this->factory->app()->get('config')->get('app.debug');
    }
}