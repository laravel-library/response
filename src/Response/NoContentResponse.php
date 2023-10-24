<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Contacts\Response;

final class NoContentResponse implements Response
{
    use Responder;

    public function message(): string
    {
        return "noContent";
    }

    public function code(): int
    {
        return 204;
    }
}