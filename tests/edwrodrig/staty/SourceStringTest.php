<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use PHPUnit\Framework\TestCase;

class SourceStringTest extends TestCase
{

    public function test_create_from_string()
    {
        $source_file = SourceString::create_from_string("some data");
        $this->assertInstanceOf(SourceString::class, $source_file);
    }

    public function test_get_content()
    {
        $source_file = SourceString::create_from_string("some data");
        $this->assertEquals("some data", $source_file->get_content());
    }
}
