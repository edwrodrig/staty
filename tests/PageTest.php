<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\PageString;
use PHPUnit\Framework\TestCase;


class PageTest extends TestCase
{

    public function testGetContent() {

	    $page = new PageString("some content", "relative");
        $this->assertEquals("relative", $page->getRelativeFilename());
        $this->assertEquals("some content", $page->getContent());
        $this->assertTrue($page->prepare());
    }



}
