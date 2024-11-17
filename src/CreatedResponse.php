<?php

declare(strict_types=1);

namespace Elephant\Response;

use Elephant\Response\Contacts\Response;
use Override;

final class CreatedResponse  implements Response
{
    use Responder;

    #[Override]
    public function message(): string
    {
        return "created";
    }

    #[Override]
    public function code(): int
    {
        return 201;
    }
}