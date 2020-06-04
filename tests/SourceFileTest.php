<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty_core\SourceFile;
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

        $source_file = SourceFile::createFromFilename($source_filename);
        $this->assertInstanceOf(SourceFile::class, $source_file);
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_create_from_string()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourceFile::createFromString("hello", $source_filename);
        $this->assertEquals("hello", $source_file->getContent());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_filename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFile::createFromFilename($source_filename);
        $this->assertEquals($source_filename, $source_file->getFilename());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_content()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFile::createFromFilename($source_filename);
        $this->assertEquals("some content", $source_file->getContent());
    }

    public function test_create_nonexistent_file()
    {
        $path = $this->root->url();
        $source_filename = $path . "/nonexistent";
        try {
            SourceFile::createFromFilename($source_filename);
            $this->fail("should except");

        } catch (ExceptionWithData $exception) {
            $this->assertEquals('source file does not exists', $exception->getMessage());
            $this->assertEquals(['filename' => $source_filename], $exception->getData());
        }

    }

}
