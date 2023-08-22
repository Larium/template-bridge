<?php

declare(strict_types=1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Cache\Cache;
use Larium\Bridge\Template\Filter\Filter;
use Twig\Cache\CacheInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class TwigTemplate implements Template
{
    /**
     * @var Environment
     */
    private $engine;

    /**
     * @var FilesystemLoader
     */
    private $fileSystem;

    public function __construct(string $path, ?Environment $twig = null)
    {
        $twig === null ? $this->setUpNew($path) : $this->setUpExisting($path, $twig);
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
        /** @var CacheInterface $engineCache */
        $engineCache = $cache->getCacheImplementation();
        $this->engine->setCache($engineCache);
    }

    public function addPath(string $path): void
    {
        $this->fileSystem->addPath($path);
    }

    private function setUpExisting(string $path, Environment $twig): void
    {
        $this->engine = $twig;
        $loader = $this->engine->getLoader();
        if ($loader instanceof FilesystemLoader) {
            $loader->addPath($path);
            $this->fileSystem = $loader;
        }
    }

    private function setUpNew(string $path): void
    {
        $this->fileSystem = new FilesystemLoader($path);
        $this->engine = new Environment($this->fileSystem);
    }
}
