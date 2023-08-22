<?php

declare(strict_types=1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Filter\UppercaseFilter;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class TwigTemplateTest extends TestCase
{
    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var Template
     */
    private $template;

    public function setUp(): void
    {
        $this->templatePath = __DIR__ . '/templates/twig';
        $this->template = new TwigTemplate($this->templatePath);
    }

    public function testShouldTwigBridgePath(): void
    {
        $engine = $this->getEngine();

        /** @var FilesystemLoader $loader */
        $loader = $engine->getLoader();
        self::assertInstanceOf(FilesystemLoader::class, $loader);
        $paths = $loader->getPaths();
        $path = reset($paths);

        self::assertEquals($this->templatePath, $path);
    }

    public function testShouldAddPath(): void
    {
        try {
            $this->template->render('test.html.twig', ['testVariable' => 'Hello World!']);
        } catch (\Twig\Error\LoaderError $e) {
            self::assertInstanceOf(\Twig\Error\LoaderError::class, $e);
        }

        $this->template->addPath(__DIR__ . '/templates/other');
        $result = $this->template->render('test.html.twig', ['testVariable' => 'Hello World!']);

        self::assertEquals('<div>Hello World!</div>', $result);
    }

    public function testTwigBridgeShouldRender(): void
    {
        $content = $this->template->render('block.html.twig');

        self::assertNotNull($content);
    }

    public function testTwigBridgeShouldRenderWithPredefinedEngine(): void
    {
        $env = new Environment(
            new FilesystemLoader([$this->templatePath])
        );
        $template = new TwigTemplate($this->templatePath, $env);
        $content = $template->render('block.html.twig');

        self::assertNotNull($content);
    }

    public function testTwigBridgeShouldAddFilters(): void
    {
        $filter = new UppercaseFilter();
        $this->template->addFilter($filter);

        $engine = $this->getEngine();

        $twigFilter = $engine->getFilter('uppercase');

        self::assertInstanceOf(TwigFilter::class, $twigFilter);

        $content = $this->template->render('uppercase-block.html.twig');
        self::assertNotNull($content);
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
        self::assertEquals($expected, $content);
    }

    private function getEngine(): Environment
    {
        $r = new \ReflectionClass($this->template);
        $p = $r->getProperty('engine');
        $p->setAccessible(true);

        return $p->getValue($this->template);
    }
}
