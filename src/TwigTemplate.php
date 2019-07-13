<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Cache\Cache;
use Larium\Bridge\Template\Filter\Filter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class TwigTemplate implements Template
{
    private $engine;

    public function __construct(string $path)
    {
        $this->engine = new Environment(
            new FilesystemLoader($path)
        );
    }

    public function render(string $template, array $params = []): string
    {
        return $this->engine->render($template, $params);
    }

    public function addFilter(Filter $filter): void
    {
        $this->engine->addFilter(
            new TwigFilter($filter->getName(), $filter->getCallable())
        );
    }

    public function disableCache(): void
    {
        $this->engine->setCache(false);
    }

    public function setCache(Cache $cache): void
    {
        $this->engine->setCache($cache);
    }
}
