<?php
declare(strict_types=1);

namespace test\labo86\staty;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\PageSymlink;
use PHPUnit\Framework\TestCase;

class PageSymlinkTest extends TestCase
{

    /**
     * @var false|string
     */
    private $path;

    public function setUp() : void {
        $this->path =  tempnam(__DIR__, 'demo');

        unlink($this->path);
        mkdir($this->path, 0777);
    }

    public function tearDown() : void {
        exec('rm -rf ' . $this->path);
    }

    /**
     * @throws ExceptionWithData
     */
    function testPageSymlink()
    {
        mkdir($this->path . '/hola');

        $f = new PageSymlink($this->path . '/hola', 'bundle/hola/');
        mkdir ( $this->path . '/bundle');
        $f->generate($this->path . '/bundle/hola/');

        $this->assertEquals('../../hola', readlink($this->path . '/bundle/hola'));
    }

    /**
     * @throws ExceptionWithData
     */
    function testPageSymlinkDirExistent()
    {
        mkdir($this->path . '/hola');
        mkdir($this->path . '/bundle/hola', 0777, true);

        $f = new PageSymlink($this->path . '/hola', 'bundle/hola/');
        $f->generate($this->path . '/bundle/hola/');


        $this->assertEquals('../../hola', readlink($this->path . '/bundle/hola'));
    }

    /**
     * @throws ExceptionWithData
     */
    function testPageSymlinkSymLinkExistent()
    {
        mkdir($this->path . '/hola');
        mkdir($this->path . '/bundle');
        symlink($this->path . '/hola', $this->path . '/bundle/hola');

        $f = new PageSymlink($this->path . '/hola', 'bundle/hola/');
        $f->generate($this->path . '/bundle/hola/');


        $this->assertEquals('../../hola', readlink($this->path . '/bundle/hola'));
    }
}
