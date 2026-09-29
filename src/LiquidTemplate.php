<?php

declare(strict_types=1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Cache\Cache;
use Larium\Bridge\Template\Filter\Filter;
use Liquid\Cache as LiquidCache;
use Liquid\FileSystem\Local;
use Liquid\Template as LiquidTemplateEngine;

class LiquidTemplate implements Template
{
    private LiquidTemplateEngine $engine;

    private Local $fileSystem;

    public function __construct(string $path, ?LiquidTemplateEngine $template = null)
    {
        $template === null ? $this->setUpNew($path) : $this->setUpExisting($path, $template);
    }

    public function render(string $template, array $params = []): string
    {
        return $this->engine->parseFile($template)->render($params);
    }

    public function addFilter(Filter $filter): void
    {
        $this->engine->registerFilter($filter->getName(), $filter->getCallable());
    }

    public function disableCache(): void
    {
        $this->engine->setCache(null);
    }

    public function setCache(Cache $cache): void
    {
        /** @var LiquidCache $engineCache */
        $engineCache = $cache->getCacheImplementation();
        $this->engine->setCache($engineCache);
    }

    public function addPath(string $path): void
    {
    }

    private function setUpNew(string $path): void
    {
        $this->fileSystem = new Local($path);
        $this->engine = new LiquidTemplateEngine();
        $this->engine->setFileSystem($this->fileSystem);
    }

    private function setUpExisting(string $path, LiquidTemplateEngine $template): void
    {
        $this->engine = $template;

    }
}
