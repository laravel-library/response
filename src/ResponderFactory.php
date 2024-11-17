<?php

namespace Elephant\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Response;
use Illuminate\Contracts\Container\Container;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class ResponderFactory implements Factory
{

    private Request $request;

    private readonly Container $container;

    public function __construct(Request $request, Container $container)
    {
        $this->request   = $request;
        $this->container = $container;
    }

    #[Override]
    public function toResponse(mixed $data = null, int $code = 0, Throwable $throwable = null): Response
    {
        if (!is_null($throwable)) {
            return new AnonymousResponse($throwable);
        }

        return match ($this->request->getMethod()) {
            'POST', 'PATCH', 'PUT' => new CreatedResponse($data, $code),
            'DELETE'               => new NoContentResponse($data, $code),
            default                => new SuccessResponse($data, $code)
        };
    }

    #[Override]
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    #[Override]
    public function app(): Container
    {
        return $this->container;
    }
}