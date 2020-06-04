<?php
declare(strict_types=1);

namespace test\edwrodrig\staty_core;

use edwrodrig\exception_with_data\ExceptionWithData;
use edwrodrig\staty_core\Util;
use PHPUnit\Framework\TestCase;
use Throwable;

class UtilTest extends TestCase
{

    /**
     * @throws Throwable
     */
    public function test_output_buffer_safe()
    {
        $output = Util::output_buffer_safe(function () {
            echo "hello";
        });
        $this->assertEquals('hello', $output);

    }

    /**
     * @throws Throwable
     */
    public function test_output_buffer_safe_exception()
    {
        $this->expectException(ExceptionWithData::class);
        $this->expectExceptionMessage("hello exception");
        Util::output_buffer_safe(function () {
            throw new ExceptionWithData(["message" => "hello exception"]);
        });

    }

    public function get_relative_path_provider()
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
     * @dataProvider get_relative_path_provider
     * @param string $expected
     * @param string $from
     * @param string $to
     */
    public function test_get_relative_path(string $expected, string $from, string $to)
    {
        $this->assertEquals($expected, Util::get_relative_path($from, $to));
    }
}
