<?php

namespace Elephant\Response\Responses;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Response;
use Symfony\Component\HttpFoundation\Request;

final readonly class ResponseFactory implements Factory
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function toResponse(): Response
    {
        return match ($this->request->getMethod()) {
            'POST', 'PATCH', 'PUT' => new CreatedResponse(),
            'DELETE'               => new NoContentResponse(),
            default                => new SuccessResponse()
        };
    }
}