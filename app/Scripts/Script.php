<?php

namespace App\Scripts;

abstract class Script
{
    abstract public function script(): string;

    public function __toString()
    {
        return $this->script();
    }
}
