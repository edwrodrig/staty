<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\PageString;
use PHPUnit\Framework\TestCase;


class PageTest extends TestCase
{

    public function test_get_content() {

	    $page = new PageString("some content", "relative");
        $this->assertEquals("relative", $page->get_relative_filename());
        $this->assertEquals("some content", $page->get_content());
        $this->assertTrue($page->prepare());
    }



}
