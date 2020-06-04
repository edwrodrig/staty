<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\Context;
use edwrodrig\staty\Page;
use edwrodrig\staty\ReaderFile;
use edwrodrig\exception_with_data\ExceptionWithData;
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
    public function test_read_pages()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $context = new Context();
        $reader = new ReaderFile($context, $source_filename, $path);

        /** @var Page[] $pages */
        $pages = iterator_to_array($reader->read_pages(), false);
        $this->assertCount(1, $pages);
        $page = $pages[0];
        $this->assertEquals("file", $page->get_relative_filename());
        $this->assertEquals("some content", $page->get_content());

    }

    /**
     * @throws ExceptionWithData
     */
    public function test_read_pages_php()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file.php";
        file_put_contents($source_filename, "<?php echo 'hello';");

        $context = new Context();
        $reader = new ReaderFile($context, $source_filename, $path);

        /**
         * @var Page[] $pages
         */
        $pages = iterator_to_array($reader->read_pages(), false);
        $this->assertCount(1, $pages);
        $page = $pages[0];
        $this->assertEquals("file", $page->get_relative_filename());
        $this->assertEquals("hello", $page->get_content());


    }

    public function get_relative_path_provider() {
        return [
            ['root/b/b.php', "/home/a.php", "/home/root/b/b.php"],
            ["../../root/b/b.php", "/home/apache/a/a.php", "/home/root/b/b.php"],
            ["../../apache/docs/b/en/b.php", "/home/root/a/a.php", "/home/apache/docs/b/en/b.php"],
            ["../../../../root/a/a.php", "/home/apache/docs/b/en/b.php", "/home/root/a/a.php"],
            ["../hello.jpg", "index.html", "../hello.jpg"],
            ["../hello.jpg", "index.html", "../hello.jpg"],
            ["como/te/va", "hello", "hello/como/te/va"],
            ["como/te/va", "hello/", "hello/como/te/va"],
            ["hello/como/te/va", "", "hello/como/te/va"],
            ["hello/como/te/va", "/", "/hello/como/te/va"]
        ];
    }

    /**
     * @dataProvider get_relative_path_provider
     * @param string $expected
     * @param string $from
     * @param string $to
     * @throws ExceptionWithData
     */
    public function test_get_relative_path(string $expected, string $from, string $to)
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        file_put_contents($source_filename, "some content");

        $context = new Context();
        $reader = new ReaderFile($context, $source_filename, $from);
        $this->assertEquals($expected, $reader->get_relative_path($to));
    }

    public function test___construct_file_exists()
    {
        $path = $this->root->url();

        $context = new Context();
        try {
            new ReaderFile($context, $path . '/file');
            $this->fail("should throw");

        } catch ( ExceptionWithData $exception ) {
            $this->assertEquals('filename does not exists', $exception->getMessage());
            $this->assertEquals(['filename' => 'vfs://root/file'], $exception->getData());
        }
    }
}
