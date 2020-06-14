<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty_core\Context;
use labo86\staty_core\PagePhp;
use labo86\staty_core\SourcePhpScript;
use labo86\exception_with_data\ExceptionWithData;
use PHPUnit\Framework\TestCase;
use Throwable;

class PagePhpTest extends TestCase
{


    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testGetContent()
    {

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello");
        $template = new PagePhp($context, "output", $source_file);
        $this->assertEquals("hello", $template->getContent());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test__construct()
    {

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello");
        $template = new PagePhp($context, "output", $source_file);
        $this->assertInstanceOf(PagePhp::class, $template);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetRelativeFilename()
    {

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello");
        $template = new PagePhp($context, "output", $source_file);
        $this->assertEquals('output', $template->getRelativeFilename());
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testPrepare()
    {

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello");
        $template = new PagePhp($context, "output", $source_file);
        $this->assertTrue($template->prepare());
    }

}
