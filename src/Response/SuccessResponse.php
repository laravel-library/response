<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Response\Contacts\Response;

final class SuccessResponse implements Response
{
    use Responder;

    public function message(): string
    {
        return "success";
    }

    public function code(): int
    {
        return 200;
    }
}