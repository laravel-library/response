<?php

declare(strict_types=1);

namespace Elephant\Response;

use Elephant\Response\Contacts\Response;
use JetBrains\PhpStorm\Pure;
use Override;

final readonly class SuccessResponse implements Response
{
    use Responder;

    #[Override]
    #[Pure]
    public function message(): string
    {
        return "success";
    }

    #[Override]
    #[Pure]
    public function code(): int
    {
        return 200;
    }
}