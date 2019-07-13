<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template\Filter;

interface Filter
{
    public function getName(): string;

    public function getCallable(): callable;
}
