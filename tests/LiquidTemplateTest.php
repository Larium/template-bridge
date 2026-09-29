<?php

declare(strict_types=1);

namespace Larium\Bridge\Template;

use Larium\Bridge\Template\Filter\UppercaseFilter;
use Liquid\FileSystem\Local;
use Liquid\Template as LiquidTemplateEngine;
use PHPUnit\Framework\TestCase;

class LiquidTemplateTest extends TestCase
{
    private string $templatePath;

    private Template $template;

    protected function setUp(): void
    {
        $this->templatePath = __DIR__ . '/templates/liquid';
        $this->template = new LiquidTemplate($this->templatePath);
    }

    public function testShouldLiquidBridgePath(): void
    {
        $engine = $this->getEngine();
        $reflection = new \ReflectionClass($engine);
        $property = $reflection->getProperty('fileSystem');
        $property->setAccessible(true);
        $fileSystem = $property->getValue($engine);

        self::assertInstanceOf(Local::class, $fileSystem);

        $reflection = new \ReflectionClass($fileSystem);
        $property = $reflection->getProperty('root');
        $property->setAccessible(true);

        self::assertEquals($this->templatePath, $property->getValue($fileSystem));
    }

    public function testLiquidBridgeShouldRender(): void
    {
        $content = $this->template->render('block');

        self::assertSame('A content', $content);
    }

    public function testLiquidBridgeShouldRenderWithPredefinedEngine(): void
    {
        $engine = new LiquidTemplateEngine();
        $engine->setFileSystem(new Local($this->templatePath));
        $template = new LiquidTemplate($this->templatePath, $engine);

        self::assertSame('A content', $template->render('block'));
    }

    public function testLiquidBridgeShouldAddFilters(): void
    {
        $this->template->addFilter(new UppercaseFilter());

        self::assertSame('A CONTENT', $this->template->render('uppercase-block'));
    }

    private function getEngine(): LiquidTemplateEngine
    {
        $reflection = new \ReflectionClass($this->template);
        $property = $reflection->getProperty('engine');
        $property->setAccessible(true);

        return $property->getValue($this->template);
    }
}
