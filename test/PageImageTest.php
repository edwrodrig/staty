<?php
declare(strict_types=1);

namespace test\labo86\staty;

use edwrodrig\image\exception\ConvertingSvgException;
use edwrodrig\image\exception\InvalidImageException;
use edwrodrig\image\exception\InvalidSizeException;
use edwrodrig\image\exception\WrongFormatException;
use ImagickException;
use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\PageImage;
use labo86\staty_core\SourceFile;
use labo86\staty_core\SourceFileTemp;
use PHPUnit\Framework\TestCase;

class PageImageTest extends TestCase
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


    function testGetCachedFile()
    {
        $f = new PageImage(SourceFileTemp::createFromString(""), 'hola.jpg');
        $this->assertEquals('hola.jpg', $f->getRelativeFilename());
    }

    /**
     * @throws ConvertingSvgException
     * @throws ExceptionWithData
     * @throws ImagickException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     */
    function testHappy()
    {

        $item = new PageImage(new SourceFile(__DIR__ . '/image/rei.jpg'), 'rei.jpg');
        $item->resizeCover(100, 100);
        $this->assertEquals('rei_100x100_cover.jpg', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);

        $item = new PageImage(new SourceFile(__DIR__ . '/image/rei.jpg'), 'rei.jpg');
        $item->resizeContain(200, 100);
        $this->assertEquals('rei_200x100_contain.jpg', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);


    }

    /**
     * @throws ConvertingSvgException
     * @throws ExceptionWithData
     * @throws ImagickException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     */
    function testNoTransformation()
    {

        $item = new PageImage(new SourceFile(__DIR__ . '/image/rei.jpg'), 'rei.jpg');
        $this->assertEquals('rei.jpg', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);
    }

    /**
     * @throws ConvertingSvgException
     * @throws ExceptionWithData
     * @throws ImagickException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     */
    function testChangeExtensionToPng()
    {

        $item = new PageImage(new SourceFile(__DIR__ . '/image/rei.jpg'), 'rei.png');
        $item->resizeContain(200, 100);
        $this->assertEquals('rei_200x100_contain.png', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);

    }

    /**
     * @throws ConvertingSvgException
     * @throws ExceptionWithData
     * @throws ImagickException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     */
    function testChangeExtensionToBmp()
    {

        $item = new PageImage(new SourceFile(__DIR__ . '/image/rei.jpg'), 'rei.bmp');
        $item->resizeContain(200, 100);

        $this->assertEquals('rei_200x100_contain.bmp', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);

    }

    /**
     * @throws ImagickException
     * @throws ConvertingSvgException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     * @throws ExceptionWithData
     */
    function testSvgFile()
    {

        $item = new PageImage(new SourceFile(__DIR__ . '/image/hw.svg'), 'hw.png');
        $item->resizeContain(200, 100);

        $this->assertEquals('hw_200x100_contain.png', $item->getRelativeFilename());

        $filename = $this->path . '/' . $item->getRelativeFilename();
        $item->generate($filename);
        $this->assertFileExists($filename);

    }
}
