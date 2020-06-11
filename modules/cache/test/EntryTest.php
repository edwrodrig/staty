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
        $filename =  $entry->getFilename(10);

        touch($directory.  '/' . $filename);

        $this->assertFileExists($directory.  '/' . $entry->getFilename(10));



    }

    public function testIsExpired() {
        $path = $this->root->url();
        $directory = $path;

        $entry = new Entry('some_id', $directory);

        $this->assertTrue($entry->isExpired(0));

        $filename = $entry->getFilename(0);
        $this->assertEquals($filename, $entry->getFilename(0), "debería ser el mismo");

        $this->assertFileNotExists($directory . '/' . $filename, "es una entrada nueva, no debería existir");


        touch($directory . '/' . $filename);
        $this->assertFileExists($directory . '/' . $filename, "acabamos de crear un archivos");


        $this->assertTrue($entry->isExpired(0), "no cambia la condición de expirado al crear un archivo");

        $entry = new Entry('some_id', $directory);
        $this->assertTrue($entry->isExpired(0), "pero si cambia al generar un nueva entrada para el mismo archivo");

        $future_time = time() + 100;
        $this->assertTrue($entry->isExpired($future_time), "debe haber expirado si le pasamos un tiempo futuro");

        $new_filename = $entry->getFilename($future_time);
        $this->assertNotEquals($filename, $new_filename);
    }




}
