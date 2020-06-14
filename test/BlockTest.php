<?php
declare(strict_types=1);

namespace test\labo86\staty;

use labo86\staty\Block;
use labo86\staty_core\Context;
use labo86\staty_core\PagePhp;
use labo86\staty_core\PageString;
use labo86\staty_core\SourceFileTemp;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{

    public function testMakePage()
    {
        $context = new Context("path");
        $page = new PagePhp($context, "folder_1/file_1", SourceFileTemp::createFromString(""));
        $block = new Block($page);


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
        $page = new PagePhp($context, "folder_1/file_1", SourceFileTemp::createFromString(""));
        $block = new Block($page);

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

    public function sprintfProvider()
    {
        return [
            ["hola", "hola", []],
            ["hola1", "hola%s", ["1"]],
            ["", "hola%s", [null]],
            ["hola'", "hola%s", ["'"]]
        ];
    }

    /**
     * @dataProvider sprintfProvider
     * @param $expected
     * @param $pattern
     * @param $args
     */
    public function testSprintf(string $expected, string $pattern, array $args)
    {
        $block = new Block(Block::thisPage());
        $this->assertEquals($expected, $block->sprintf($pattern, ...$args));
    }

}
