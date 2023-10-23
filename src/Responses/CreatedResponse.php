<?php

declare(strict_types=1);

namespace Elephant\Response\Responses;

use Elephant\Response\Responder;

final  class CreatedResponse extends Responder
{
    public function message(): string
    {
        return "created";
    }

    public function code(): int
    {
        return 201;
    }
}