<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\Context;
use edwrodrig\staty\Page;
use edwrodrig\staty\ReaderDirectory;
use edwrodrig\util\Exception;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ReaderDirectoryTest extends TestCase
{
    protected vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }


    /**
     * @throws Exception
     */
    public function test_read_pages()
    {
        $path = $this->root->url();
        $source_filename_1 = $path . "/file_1";
        file_put_contents($source_filename_1, "some content 1");

        $source_filename_2 = $path . "/file_2";
        file_put_contents($source_filename_2, "some content 2");

        $context = new Context();

        $reader = new ReaderDirectory($context, $path);

        /**
         * @var Page[] $pages
         */
        $pages = iterator_to_array($reader->read_pages(), false);

        $this->assertCount(2, $pages);
        $page = $pages[0];
        $this->assertEquals("file_1", $page->get_relative_filename());
        $this->assertEquals("some content 1" , $page->get_content());

        $page = $pages[1];
        $this->assertEquals("file_2", $page->get_relative_filename());
        $this->assertEquals("some content 2" , $page->get_content());

    }

    public function test___construct_not_existent_directory()
    {
        $path = $this->root->url();

        $context = new Context();
        try {
            new ReaderDirectory($context, $path . '/not_existent');
            $this->fail("should throw");

        } catch ( Exception $exception ) {
            $this->assertEquals([
                'message' => 'directory does not exists',
                'data' => [
                    'directory_path' => 'vfs://root/not_existent'
                ]
            ], $exception->get_structured_data());
        }
    }

    public function test___construct_file_exists()
    {
        $path = $this->root->url();
        touch($path . '/file');

        $context = new Context();
        try {
            new ReaderDirectory($context, $path . '/file');
            $this->fail("should throw");

        } catch ( Exception $exception ) {
            $this->assertEquals([
                'message' => 'directory path is a file',
                'data' => [
                    'directory_path' => 'vfs://root/file'
                ]
            ], $exception->get_structured_data());
        }
    }
}
