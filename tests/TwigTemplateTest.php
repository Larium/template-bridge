<?php

declare(strict_types = 1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Filter\UppercaseFilter;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class TwigTemplateTest extends TestCase
{
    private $templatePath;

    private $template;

    public function setUp(): void
    {
        $this->templatePath = __DIR__ . '/templates/twig';
        $this->template = new TwigTemplate($this->templatePath);
    }

    public function testShouldTwigBridgePath(): void
    {
        $engine = $this->getEngine();

        $this->assertInstanceOf(FilesystemLoader::class, $engine->getLoader());
        $paths = $engine->getLoader()->getPaths();
        $path = reset($paths);

        $this->assertEquals($this->templatePath, $path);
    }

    public function testTwigBridgeShouldRender(): void
    {
        $content = $this->template->render('block.html.twig');

        $this->assertNotNull($content);
    }

    public function testTwigBridgeShouldAddFilters(): void
    {
        $filter = new UppercaseFilter();
        $this->template->addFilter($filter);

        $engine = $this->getEngine();

        $twigFilter = $engine->getFilter('uppercase');

        $this->assertInstanceOf(TwigFilter::class, $twigFilter);

        $content = $this->template->render('uppercase-block.html.twig');
        $this->assertNotNull($content);
        $expected = <<<CONTENT
<html>
    <head>
        <title>A title</title>
            </head>
    <body>
        <p>A CONTENT</p>
    </body>
</html>

CONTENT;
        $this->assertEquals($expected, $content);
    }

    private function getEngine(): Environment
    {
        $r = new \ReflectionClass($this->template);
        $p = $r->getProperty('engine');
        $p->setAccessible(true);

        return $p->getValue($this->template);
    }
}
