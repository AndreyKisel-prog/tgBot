<?php

namespace App\Transformers;

abstract class BaseTransformer implements TransformerInterface
{
    abstract function handle(): array;
}

