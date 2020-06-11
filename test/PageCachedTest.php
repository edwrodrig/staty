<?php
declare(strict_types=1);

namespace test\labo86\staty;

use labo86\cache\Cache;
use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\PageCached;
use labo86\staty_core\PageString;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class PageCachedTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws ExceptionWithData
     */
    public function testBasic() {
        $path = $this->root->url();
        $directory = $path . "/www";

        mkdir($directory, 0777, true);

        $cache = new Cache($directory);

        $source = new PageString("contenido", "cache/string");
        $page = new PageCached($source, $cache);
        $this->assertTrue($page->isExpired());
        $filename = $directory . '/' . $page->getRelativeFilename();
        $page->generate($filename);
        $this->assertFileExists($filename);

        $cache = new Cache($directory);
        $page = new PageCached($source, $cache);
        $this->assertFalse($page->isExpired());
        $new_filename =  $directory . '/' . $page->getRelativeFilename();
        $this->assertEquals($filename, $new_filename);


    }
}
