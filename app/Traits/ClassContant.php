<?php

namespace App\Traits;

use ReflectionClass;

trait ClassContant
{
    public function getConstants(): array
    {
        $reflectionClass = new ReflectionClass($this);

        return $reflectionClass->getConstants();
    }
}
