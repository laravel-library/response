<?php

namespace Elephant\Response\Response\Contacts;

interface Responsable
{
    public function toResponse(): mixed;
}