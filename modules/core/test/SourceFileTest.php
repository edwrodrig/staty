<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty\ErrMsg;
use labo86\staty_core\SourceFile;
use labo86\exception_with_data\ExceptionWithData;
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
    public function testGetFilename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = new SourceFile($source_filename);
        $this->assertEquals($source_filename, $source_file->getFilename());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetContent()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = new SourceFile($source_filename);
        $this->assertEquals("some content", $source_file->getContent());
    }

    public function testCreateNonexistentFile()
    {
        $path = $this->root->url();
        $source_filename = $path . "/nonexistent";
        try {
            new SourceFile($source_filename);
            $this->fail("should except");

        } catch (ExceptionWithData $exception) {
            $this->assertEquals(ErrMsg::SOURCE_FILE_DOES_NOT_EXIST, $exception->getMessage());
            $this->assertEquals(['filename' => $source_filename], $exception->getData());
        }

    }

}
