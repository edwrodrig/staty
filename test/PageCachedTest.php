<?php
declare(strict_types=1);

namespace test\labo86\staty;

use labo86\cache\Cache;
use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\PageCached;
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
        $directory = $path . "/www/cache";

        mkdir($directory, 0777, true);

        $cache = new Cache($directory);


/*
        $entry = $cache->getEntry('hola');
        $this->assertTrue($entry->isExpired(10));
        $filename = $entry->getFilename(10);
        $this->assertEquals('a_hola', $filename);
*/
    }
}
