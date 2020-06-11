<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty_core\Generator;
use labo86\staty_core\PageString;
use labo86\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{

    protected vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws ExceptionWithData
     */
    public function testPrepareOutputFilename()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        $generator = new Generator($folder);
        $filename = $generator->prepareOutputFilename("hello");
        $this->assertEquals($folder . '/hello', $filename);
        $this->assertDirectoryExists($folder);

        $filename = $generator->prepareOutputFilename("folder/file_2");
        $this->assertEquals($folder . '/folder/file_2', $filename);
        $this->assertDirectoryExists($folder . '/folder');
    }

    public function testPrepareOutputFilenameFileExist()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        mkdir($folder, 0777);
        touch($folder . '/existent_file');

        try {
            $generator = new Generator($folder);
            $generator->prepareOutputFilename("/existent_file/hello");
            $this->fail("should fail");

        } catch ( ExceptionWithData $exception ) {
            $this->assertEquals('target directory is not a directory', $exception->getMessage());
            $this->assertEquals([
                        'relative_filename' => '/existent_file/hello',
                        'directory_path' => 'vfs://root/folder//existent_file',
                        'output_directory_path' => 'vfs://root/folder'
                    ], $exception->getData());
        }
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGenerate()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        $generator = new Generator($folder);

        $pages = [
            new PageString("content_1", "page_1"),
            new PageString("content_2", "folder/page_2"),
            new PageString("content_3", "page_3")
        ];

        $generator->setPageList($pages);
        $generated_pages = $generator->generate();
        $this->assertEquals(["page_1", "folder/page_2", "page_3"], $generated_pages);

        $this->assertFileExists($folder . '/page_1');
        $this->assertEquals("content_1", file_get_contents($folder . '/page_1'));

        $this->assertFileExists($folder . '/folder/page_2');
        $this->assertEquals("content_2", file_get_contents($folder . "/folder/page_2"));

        $this->assertFileExists($folder . '/page_3');
        $this->assertEquals("content_3", file_get_contents($folder . "/page_3"));
    }
}
