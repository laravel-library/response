<?php

declare(strict_types=1);

namespace Elephant\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Contacts\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class  Responder implements Responsable, Response
{

    private Factory $factory;

    private SymfonyResponse $response;

    public function __construct(Factory $factory, SymfonyResponse $response)
    {
        $this->factory = $factory;

        $this->response = $response;
    }

    public function toResponse(): JsonResponse
    {
        $response = $this->prepareResponse();

        return new JsonResponse($this->prepareContent($response));
    }

    private function prepareResponse(): array
    {
        $responder = $this->factory->toResponse();

        return [
            'message' => $responder->message(),
            'code'    => $responder->code(),
            'data'    => null,
        ];
    }

    private function prepareContent(array $response): array
    {
        $content = $this->response->getContent();

        if ($this->validateJson($content)) {
            $content = json_decode($content, true);

            return array_merge($response, ['message' => $content, 'code' => $content['code']]);
        }

        if (!empty($content)) {

            $attribute = is_string($content) ? 'message' : 'data';

            $response[$attribute] = $content;
        }

        return $response;
    }

    private function validateJson(mixed $content): bool
    {
        json_decode($content);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function setResponse(SymfonyResponse $response): Responsable
    {
        $this->response = $response;

        return $this;
    }
}