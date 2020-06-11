<?php
declare(strict_types=1);

namespace test\labo86\cache;

use labo86\cache\Cache;
use labo86\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
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
        $directory = $path . "/cache";

        $cache = new Cache($directory);
        $entry = $cache->getEntry('hola');
        $this->assertTrue($entry->isExpired(10));
        $filename = $entry->getFilename(10);
        $this->assertEquals('a_hola', $filename);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testClearUnusedEmpty() {
        $path = $this->root->url();
        $directory = $path . "/cache";

        $cache = new Cache($directory);
        $entry = $cache->getEntry('hola');

        $filename = $entry->getFilename(10);
        $this->assertEquals('a_hola', $filename);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testClearUnused() {
        $path = $this->root->url();
        $directory = $path . "/cache";

        $cache = new Cache($directory);
        $entry = $cache->getEntry('hola');
        $filename = $entry->getFilename(10);
        $this->assertEquals('a_hola', $filename);

        touch($directory . '/' . $filename);

        $this->assertFileExists($directory . '/' . $entry->getFilename(10));
    }

    /**
     * @throws ExceptionWithData
     */
    public function testClearUnusedExpired() {
        $path = $this->root->url();
        $directory = $path . "/cache";

        $cache = new Cache($directory);
        $entry = $cache->getEntry('hello');
        touch($directory . '/' . $entry->getFilename(10));
        $this->assertFileExists($directory . '/' . $entry->getFilename(10));

    }

    /**
     * @throws ExceptionWithData
     */
    public function testRecoverFromCached() {
        $path = $this->root->url();
        $directory = $path . "/cache";

        $cache = new Cache($directory);
        $entry = $cache->getEntry('hello');
        $filename = $entry->getFilename(10);

        touch($directory . '/' . $filename);

        $this->assertFileExists($directory . '/' . $entry->getFilename(10));


        $cache = new Cache($directory);
        $entry = $cache->getEntry('hello');
        $this->assertFalse($entry->isExpired(0));

        $this->assertFalse($entry->isExpired(10));

        $this->assertTrue($entry->isExpired(time() + 100));

    }


    public function testCacheDirectoryFileExists() {
        $path = $this->root->url();
        $directory = $path . "/cache";

        try {
             touch($directory);

             new Cache($directory);
             $this->fail('should throw');

        } catch (ExceptionWithData $exception) {
            $this->assertEquals("cache directory is a file", $exception->getMessage());
            $this->assertEquals(["directory" => $directory], $exception->getData());
        }

    }

    public function testCacheDirectoryFailToCreate() {
        $path = $this->root->url();
        $directory = $path . "/cache/can_not_be_created";

        try {
            touch($path . '/cache');


            new Cache($directory);
            $this->fail('should throw');

        } catch (ExceptionWithData $exception) {
            $this->assertEquals("error creating cache directory", $exception->getMessage());
            $this->assertEquals(["directory" => $directory], $exception->getData());
        }

    }

}
