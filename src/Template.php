<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Cache\Cache;
use Larium\Bridge\Template\Filter\Filter;

interface Template
{
    public function render(string $template, array $params = []);

    public function addFilter(Filter $filter): void;

    public function disableCache(): void;

    public function setCache(Cache $cache): void;
}
