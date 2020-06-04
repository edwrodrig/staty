<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\SourceFile;
use edwrodrig\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class SourceFileTest extends TestCase
{

    protected vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }


    /**
     * @throws ExceptionWithData
     */
    public function test_create_from_filename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFile::create_from_filename($source_filename);
        $this->assertInstanceOf(SourceFile::class, $source_file);
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_create_from_string()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourceFile::create_from_string("hello", $source_filename);
        $this->assertEquals("hello", $source_file->get_content());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_filename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFile::create_from_filename($source_filename);
        $this->assertEquals($source_filename, $source_file->get_filename());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_content()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFile::create_from_filename($source_filename);
        $this->assertEquals("some content", $source_file->get_content());
    }

    public function test_create_nonexistent_file()
    {
        $path = $this->root->url();
        $source_filename = $path . "/nonexistent";
        try {
            SourceFile::create_from_filename($source_filename);
            $this->fail("should except");

        } catch (ExceptionWithData $exception) {
            $this->assertEquals('source file does not exists', $exception->getMessage());
            $this->assertEquals(['filename' => $source_filename], $exception->getData());
        }

    }

}
