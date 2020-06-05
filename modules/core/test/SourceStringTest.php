<?php
declare(strict_types=1);

namespace test\edwrodrig\staty_core;

use edwrodrig\staty_core\SourceString;
use PHPUnit\Framework\TestCase;

class SourceStringTest extends TestCase
{

    public function testCreateFromString()
    {
        $source_file = SourceString::createFromString("some data");
        $this->assertInstanceOf(SourceString::class, $source_file);
    }

    public function testGetContent()
    {
        $source_file = SourceString::createFromString("some data");
        $this->assertEquals("some data", $source_file->getContent());
    }
}
