<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Response\Contacts\Response;
use Override;

final class NoContentResponse implements Response
{
    use Responder;

    #[Override]
    public function message(): string
    {
        return "noContent";
    }

    public function code(): int
    {
        return 204;
    }
}