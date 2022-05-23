<?php

namespace App\Transformers;

abstract class BaseTransformer implements TransformerInterface
{
    abstract public function handle(): array;
}

