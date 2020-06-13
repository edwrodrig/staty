<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty_core\SourcePhpScript;
use labo86\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class SourcePhpScriptTest extends TestCase
{
    public function testStripExtension() {
        $this->assertEquals("hello.html", SourcePhpScript::stripExtension("hello.html.php"));
    }

}
