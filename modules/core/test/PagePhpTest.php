<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty_core\Context;
use labo86\staty_core\PageString;
use labo86\staty_core\PagePhp;
use labo86\staty_core\SourcePhpScript;
use labo86\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Throwable;

class PagePhpTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp() : void {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testGetContent()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PagePhp($context, "output", $source_file);
        $this->assertEquals("hello", $template->getContent());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test__construct()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PagePhp($context, "output", $source_file);
        $this->assertInstanceOf(PagePhp::class, $template);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetRelativeFilename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PagePhp($context, "output", $source_file);
        $this->assertEquals('output', $template->getRelativeFilename());
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testPrepare()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PagePhp($context, "output", $source_file);
        $this->assertTrue($template->prepare());
    }

}
