<?php

namespace Elephant\Response\Contacts;

interface Responsable
{
    public function toResponse(): mixed;
}