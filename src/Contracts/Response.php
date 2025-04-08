<?php

namespace Elephant\Response\Contacts;

interface Response extends Responsable
{
    public function message(): string;

    public function code(): int;
}