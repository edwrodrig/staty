<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty_core\SourceFile;
use labo86\staty_core\SourceFileTemp;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class SourceFileTempTest extends TestCase
{
    protected vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }


    /**
     * @throws ExceptionWithData
     */
    public function testCreateFromFilename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $source_file = SourceFileTemp::createFromFilename($source_filename);
        $this->assertInstanceOf(SourceFile::class, $source_file);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testCreateFromString()
    {
        $source_file = SourceFileTemp::createFromString("hello");
        $this->assertEquals("hello", $source_file->getContent());
    }
}
