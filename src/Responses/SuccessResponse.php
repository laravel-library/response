<?php

declare(strict_types=1);

namespace Elephant\Response\Responses;

use Elephant\Response\Responder;

final  class SuccessResponse extends Responder
{

    public function message(): string
    {
        return "success";
    }

    public function code(): int
    {
        return 200;
    }
}