<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Response\Contacts\Response;

final class CreatedResponse  implements Response
{
    use Responder;

    public function message(): string
    {
        return "created";
    }

    public function code(): int
    {
        return 201;
    }
}