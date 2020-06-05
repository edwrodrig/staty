<?php
declare(strict_types=1);

namespace test\edwrodrig\staty_core;

use edwrodrig\staty_core\Util;
use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;

class UtilTest extends TestCase
{

    /**
     * @throws Throwable
     */
    public function testOutputBufferSafe()
    {
        $output = Util::outputBufferSafe(function () {
            echo "hello";
        });
        $this->assertEquals('hello', $output);

    }

    /**
     * @throws Throwable
     */
    public function testOutputBufferSafeException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("hello exception");
        Util::outputBufferSafe(function () {
            throw new Exception("hello exception");
        });

    }

    public function getRelativePathProvider()
    {
        return [
            ['root/b/b.php', "/home/a.php", "/home/root/b/b.php"],
            ["../../root/b/b.php", "/home/apache/a/a.php", "/home/root/b/b.php"],
            ["../../apache/hello/b/en/b.php", "/home/root/a/a.php", "/home/apache/hello/b/en/b.php"],
            ["../../../../root/a/a.php", "/home/apache/hello/b/en/b.php", "/home/root/a/a.php"],
            ["../hello.jpg", "index.html", "../hello.jpg"],
            ["../hello.jpg", "index.html", "../hello.jpg"],
            ["como/te/va", "hello", "hello/como/te/va"],
            ["como/te/va", "hello/", "hello/como/te/va"],
            ["hello/como/te/va", "", "hello/como/te/va"],
            ["hello/como/te/va", "/", "/hello/como/te/va"],
            ["file1", "vfs://root", "vfs://root/file1"],
            ["file2", "root", "root/file2"],
            ["file3", "/root", "/root/file3"],
            ["file1", "vfs://root/", "vfs://root/file1"]
        ];
    }

    /**
     * @dataProvider getRelativePathProvider
     * @param string $expected
     * @param string $from
     * @param string $to
     */
    public function testGetRelativePath(string $expected, string $from, string $to)
    {
        $this->assertEquals($expected, Util::getRelativePath($from, $to));
    }
}
