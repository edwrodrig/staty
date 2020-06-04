<?php
declare(strict_types=1);

namespace test\edwrodrig\staty_core;

use edwrodrig\staty_core\Context;
use edwrodrig\staty_core\PageString;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function testPrepare()
    {
        $page = new class("content", "some_file") extends PageString {

            public bool $prepared = false;

            public function prepare() : bool {
                $this->prepared = true;
                return true;
            }
        };

        $this->assertFalse($page->prepared);
        $context = new Context();
        $this->assertTrue($context->prepare($page));
        $this->assertTrue($page->prepared);
        $this->assertFalse($context->prepare($page));

        $page->prepared = false;
        $this->assertFalse($context->prepare($page));
        $this->assertFalse($page->prepared);

        $page_list = $context->getPreparedPageList();
        $this->assertCount(1, $page_list);
        $this->assertEquals($page, $page_list[$page->getRelativeFilename()]);
    }

    public function testGetAbsolutePath()
    {
        $context = new Context('something');
        $this->assertEquals("something", $context->getAbsolutePath());
    }


    public function testGetLang()
    {
        $context = new Context();
        setlocale(LC_ALL, 'es_CL.utf-8');
        $this->assertEquals('es_CL', $context->getLang());

        setlocale(LC_ALL, 'en_US.utf-8');
        $this->assertEquals('en_US', $context->getLang());

    }
}
