<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\Context;
use edwrodrig\staty\PageString;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function test_prepare()
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

        $page_list = $context->get_prepared_page_list();
        $this->assertCount(1, $page_list);
        $this->assertEquals($page, $page_list[$page->get_relative_filename()]);
    }

    public function test_get_absolute_path()
    {
        $context = new Context('something');
        $this->assertEquals("something", $context->get_absolute_path());
    }


    public function test_get_lang()
    {
        $context = new Context();
        setlocale(LC_ALL, 'es_CL.utf-8');
        $this->assertEquals('es_CL', $context->get_lang());

        setlocale(LC_ALL, 'en_US.utf-8');
        $this->assertEquals('en_US', $context->get_lang());

    }
}
