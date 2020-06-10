<?php
declare(strict_types=1);

namespace test\labo86\cache;

use labo86\cache\Entry;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    public function testBasicEntry() {
        $path = $this->root->url();
        $directory = $path . "/file";

        mkdir($directory);

        $entry = new Entry('some_id', $directory);
        $this->assertEquals('some_id', $entry->getId());
        $this->assertEquals($directory. '/some_id', $entry->getFilename());
        $this->assertEquals(0, $entry->getModificationTime());

        touch($entry->getFilename());

        $modificationTime = $entry->getModificationTime();
        $this->assertNotEquals(0, $modificationTime);
        $this->assertEquals($modificationTime, $entry->getModificationTime());

        $entry = new Entry('some_id', $directory);

        $this->assertEquals($modificationTime, $entry->getModificationTime());
    }



}
