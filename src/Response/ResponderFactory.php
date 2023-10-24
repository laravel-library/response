<?php

namespace Elephant\Response\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Response;
use Symfony\Component\HttpFoundation\Request;

final class ResponderFactory implements Factory
{

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function toResponse(mixed $data = null, int $code = 0): Response
    {
        return match ($this->request->getMethod()) {
            'POST', 'PATCH', 'PUT' => new CreatedResponse($data, $code),
            'DELETE'               => new NoContentResponse($data, $code),
            default                => new SuccessResponse($data, $code)
        };
    }
}