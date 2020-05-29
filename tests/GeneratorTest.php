<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\Generator;
use edwrodrig\staty\PageString;
use edwrodrig\util\Exception;
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
     * @throws Exception
     */
    public function test_prepare_output_filename()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        $generator = new Generator($folder);
        $filename = $generator->prepare_output_filename("hello");
        $this->assertEquals($folder . '/hello', $filename);
        $this->assertDirectoryExists($folder);

        $filename = $generator->prepare_output_filename("folder/file_2");
        $this->assertEquals($folder . '/folder/file_2', $filename);
        $this->assertDirectoryExists($folder . '/folder');
    }

    public function test_prepare_output_filename_file_exist()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        mkdir($folder, 0777);
        touch($folder . '/existent_file');

        try {
            $generator = new Generator($folder);
            $generator->prepare_output_filename("/existent_file/hello");
            $this->fail("should fail");

        } catch ( Exception $exception ) {
            $this->assertEquals([
                'message' => 'target directory is not a directory',
                    'data' => [
                        'relative_filename' => '/existent_file/hello',
                        'directory_path' => 'vfs://root/folder//existent_file',
                        'output_directory_path' => 'vfs://root/folder'
                    ]
            ], $exception->get_structured_data());
        }
    }

    /**
     * @throws Exception
     */
    public function test_generate()
    {
        $path = $this->root->url();
        $folder = $path . "/folder";
        $generator = new Generator($folder);

        $pages = [
            new PageString("content_1", "page_1"),
            new PageString("content_2", "folder/page_2"),
            new PageString("content_3", "page_3")
        ];

        $generator->set_page_list($pages);
        $generator->generate();

        $this->assertFileExists($folder . '/page_1');
        $this->assertEquals("content_1", file_get_contents($folder . '/page_1'));

        $this->assertFileExists($folder . '/folder/page_2');
        $this->assertEquals("content_2", file_get_contents($folder . "/folder/page_2"));

        $this->assertFileExists($folder . '/page_3');
        $this->assertEquals("content_3", file_get_contents($folder . "/page_3"));
    }
}
