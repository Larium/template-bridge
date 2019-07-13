<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template\Filter;

class UppercaseFilter implements Filter
{
    private const NAME = 'uppercase';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getCallable(): callable
    {
        return function (string $text) {
            return strtoupper($text);
        };
    }
}
