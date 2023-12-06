<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Response\Contacts\Response;
use Override;

final class SuccessResponse implements Response
{
    use Responder;

    #[Override]
    public function message(): string
    {
        return "success";
    }

     #[Override]
    public function code(): int
    {
        return 200;
    }
}