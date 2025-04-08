<?php

namespace Elephant\Response;

use Elephant\Response\Contacts\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Override;
use Throwable;

readonly class AnonymousResponse implements Response
{
    private Throwable $throwable;

    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    #[Override] public function toResponse(): array
    {
        return [
            'msg'       => $this->throwable->getMessage(),
            'code'      => $this->isZeroStatus() ? $this->code() : $this->throwable->getCode(),
            'exception' => is_null($this->throwable->getPrevious())
                ? \Exception::class
                : get_class($this->throwable->getPrevious()),
            'file'      => $this->throwable->getFile(),
            'line'      => $this->throwable->getLine(),
            'trace'     => Collection::make($this->throwable->getTrace())
                ->map(fn(array $trace): array => Arr::except($trace, ['args'])),
        ];
    }

    private function isZeroStatus(): bool
    {
        return $this->throwable->getCode() <= 0;
    }

    #[Override] public function message(): string
    {
        return 'Anonymous.errors';
    }

    #[Override] public function code(): int
    {
        return 500;
    }
}
