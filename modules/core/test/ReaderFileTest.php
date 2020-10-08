<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty\ErrMsg;
use labo86\staty_core\Context;
use labo86\staty_core\Page;
use labo86\staty_core\ReaderFile;
use labo86\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ReaderFileTest extends TestCase
{
    protected vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetPage()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $context = new Context();
        $reader = new ReaderFile($context, $source_filename, $path);

        $page = $reader->getPage();

        $this->assertEquals("file", $page->getRelativeFilename());
        $this->assertEquals("some content", $page->getContent());

    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetPagePhp()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file.php";
        file_put_contents($source_filename, "<?php echo 'hello';");

        $context = new Context();
        $reader = new ReaderFile($context, $source_filename, $path);

        $page = $reader->getPage();
        $this->assertEquals("file", $page->getRelativeFilename());
        $this->assertEquals("hello", $page->getContent());


    }

    public function testConstructFileExists()
    {
        $path = $this->root->url();

        $context = new Context();
        try {
            new ReaderFile($context, $path . '/file');
            $this->fail("should throw");

        } catch ( ExceptionWithData $exception ) {
            $this->assertEquals(ErrMsg::FILENAME_DOES_NOT_EXIST, $exception->getMessage());
            $this->assertEquals(['filename' => 'vfs://root/file'], $exception->getData());
        }
    }
}
