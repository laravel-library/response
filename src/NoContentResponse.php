<?php

declare(strict_types=1);

namespace Elephant\Response;

use Elephant\Response\Contacts\Response;
use Override;

final readonly class NoContentResponse implements Response
{
    use Responder;

    #[Override]
    public function message(): string
    {
        return "noContent";
    }

    #[Override]
    public function code(): int
    {
        return 204;
    }
}