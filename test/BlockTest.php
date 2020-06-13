<?php
declare(strict_types=1);

namespace test\labo86\staty;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\Block;
use labo86\staty_core\Context;
use labo86\staty_core\PageInfo;
use labo86\staty_core\PagePhp;
use labo86\staty_core\PageString;
use labo86\staty_core\SourcePhpScript;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{

    public function testMakePage()
    {
        $context = new Context("path");
        $page_info = new PageInfo($context, "folder_1/file_1", "file");
        $block = new Block($page_info);


        $page = new PageString("hello", "folder_2/file_2");
        $this->assertEquals("../folder_2/file_2", $block->makePage($page));

        $page_list = $context->getPreparedPageList();
        $this->assertCount(1, $page_list);
        $this->assertArrayHasKey("folder_2/file_2", $page_list);
        $inside_file = $page_list["folder_2/file_2"];
        $this->assertEquals("hello", $inside_file->getContent());
        $this->assertEquals("folder_2/file_2", $inside_file->getRelativeFilename());

    }

    public function testMakePagePrepare()
    {
        $context = new Context("path");
        $page_info = new PageInfo($context, "folder_1/file_1", "file");
        $block = new Block($page_info);

        $page = new class("content", "folder_2/file_2") extends PageString {

            public bool $prepared = false;
            public string $change = "prepare_not_called";

            public function prepare() : bool {
                $this->prepared = true;
                $this->change = "prepare_called";
                return true;
            }
        };

        $this->assertFalse($page->prepared);
        $this->assertEquals("prepare_not_called", $page->change);

        $this->assertEquals("../folder_2/file_2", $block->makePage($page));
        $this->assertTrue($page->prepared);
        $this->assertEquals("prepare_called", $page->change);

        $page->change = "prepare_already_called";
        $this->assertEquals("../folder_2/file_2", $block->makePage($page));

        $this->assertTrue($page->prepared);
        $this->assertEquals("prepare_already_called", $page->change);
        $this->assertEquals("../folder_2/file_2", $block->makePage($page));
    }

}
