<?php

namespace Elephant\Response;

use Override;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Collection;


class Reportable
{

    private Request $request;

    public function __construct(Request $request, Container $container)
    {
        $this->request   = $request;
        $this->container = $container;
    }

    #[Override]
    public function toResponse(mixed $data): array
    {
        return match ($this->request->getMethod()) {
            'POST', 'PATCH', 'PUT' => $this->reportJsonable($data, 201, 'created'),
            'DELETE'               => $this->reportJsonable($data, 204, 'deleted'),
            default                => $this->reportJsonable($data, 200, 'success')
        };
    }

    private function reportJsonable(mixed $content, int $status, string $message): array
    {
        $response = ['message' => $message, 'status' => $status, 'data' => null];

        if (is_string($content)) {
            $response['message'] = $content;
        }

        if (is_array($content)) {
            $response['data'] = $content;
        }

        return $response;
    }

    public function reportThrowable(Throwable $exception): array
    {
        return [
            'msg'       => $exception->getMessage(),
            'code'      => $exception->getCode() <= 0 ? 500 : $exception->getCode(),
            'exception' => is_null($exception->getPrevious())
                ? \Exception::class
                : get_class($exception->getPrevious()),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => Collection::make($exception->getTrace())
                ->map(fn(array $trace): array => Arr::except($trace, ['args'])),
        ];
    }
}
