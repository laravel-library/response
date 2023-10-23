<?php

declare(strict_types=1);

namespace Elephant\Response\Responses;

use Elephant\Response\Responder;

final  class NoContentResponse extends Responder
{

    public function message(): string
    {
        return "noContent";
    }

    public function code(): int
    {
        return 204;
    }
}