<?php
declare(strict_types=1);

namespace test\labo86\staty_core;

use labo86\staty_core\Util;
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
            ['root/b/b.php', "/home/", "/home/root/b/b.php"],
            ["../../root/b/b.php", "/home/apache/a/", "/home/root/b/b.php"],
            ["../../apache/hello/b/en/b.php", "/home/root/a/", "/home/apache/hello/b/en/b.php"],
            ["../../../../root/a/a.php", "/home/apache/hello/b/en/", "/home/root/a/a.php"],
            ["../hello.jpg", "", "../hello.jpg"],
            ["../hello.jpg", "", "../hello.jpg"],
            ["hello/como/te/va", "hello2", "hello/como/te/va"],
            ["como/te/va", "hello/", "hello/como/te/va"],
            ["hello/como/te/va", "", "hello/como/te/va"],
            ["hello/como/te/va", "/", "/hello/como/te/va"],
            ["file1", "vfs://root/", "vfs://root/file1"],
            ["root/file2", "root", "root/file2"],
            ["file2", "root/", "root/file2"],
            ["file3", "/root/", "/root/file3"],
            ["hello.b", "hello.a", "hello.b"],
            ["file1", "vfs://root/", "vfs://root/file1"],
            ["ws.b", "/home/some/../ws.a", "/home/modules/../ws.b"],
            ["ws.php", "/home/some/../", "/home/modules/../ws.php"],
            ["hola/ws.php", "some/..", "some/../hola/ws.php"],
            ["ws.php", "../", "../ws.php"],
            ["../../ws/www/ws.php", "www/ws/ws.php", "ws/www/ws.php"],
            ["../../../ws/www/ws.php", "/home/www/ws/ws.php", "/home/../ws/www/ws.php"],
            ["../../../ws/www/ws.php", "/home/www/ws/", "/home/../ws/www/ws.php"],
            ["../../../ws/www/ws.php", "/home/www/ws/", "/home/src/../../ws/www/ws.php"],
            ["../../../ws/www/ws.php", "/home/scripts/../modules/site/www/ws/", "/home/modules/site/src/../../ws/www/ws.php"],
            ["../../../ws/www/ws.php", "/home/edwin/Projects/mypage/scripts/../modules/site/www/ws/", "/home/edwin/Projects/mypage/modules/site/src/../../ws/www/ws.php"],
            ["../../hola", "/home/edwin/Projects/staty/test/demouoZd0m/bundle/hola/", "/home/edwin/Projects/staty/test/demouoZd0m/hola"],


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
        $this->assertEquals($expected, Util::getRelativePath($from, $to), " From : " . $from);
    }

    public function getAbsolutePathProvider()
    {
        return [
            ["expected", "expected"],
            ["../expected", "../expected"],
            ["..", "../expected/.."],
            ["../..", "../expected/../.."],
            ["../../", "../expected/../../"],
            ["../../", "../expected/.././../"],
            ["/expected", "/expected"],
            ["", "/expected/.."],
            ["", "/expected/../.."],
        ];
    }

    /**
     * @dataProvider getAbsolutePathProvider
     * @param $expected
     * @param $actual
     */


    public function testGetAbsolutePath(string $expected, string $actual) {
        $this->assertEquals($expected, Util::getNormalizedPath($actual), $actual);
    }
}
