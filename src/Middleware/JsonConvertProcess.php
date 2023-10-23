<?php

declare(strict_types=1);

namespace Elephant\Response\Middleware;

use Closure;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Contacts\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class JsonConvertProcess
{

    protected Responsable|Response $response;

    public function __construct(Responsable|Response $response)
    {
        $this->response = $response;
    }

    public function handle(Request $request, Closure $next): JsonResponse
    {

        $content = $next($request);

        return $this->response->setResponse($content)->toResponse();
    }
}