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
    private vfsStreamDirectory $root;

    public function setUp() : void {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassNoDoc()
    {
        $source_file = SourcePhpScript::createFromString("hello");
        $this->assertEquals("labo86\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocNoVar()
    {
        $source_file = SourcePhpScript::createFromString('<?php /** @silent */');
        $this->assertEquals("labo86\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocVarNoTemplate()
    {
        $source_file = SourcePhpScript::createFromString('<?php /** @var some $hello **/');
        $this->assertEquals("labo86\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    public function testGetTemplateClassDocVarTemplateUnknown()
    {
        try {
            SourcePhpScript::createFromString('<?php /** @var invalid $template **/');
            $this->fail("Should throw");
        } catch ( ExceptionWithData $exception ) {

            $this->assertEquals('invalid template class', $exception->getMessage());
            $data = $exception->getData();
            $this->assertArrayHasKey('filename', $data);
            $this->assertArrayHasKey('template_class', $data);
            $this->assertEquals('invalid', $data['template_class']);

        }
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocVarTemplateKnown()
    {
        $source_file = SourcePhpScript::createFromString('<?php /** @var labo86\staty_core\PageTemplateDummy $template **/');
        $this->assertEquals('labo86\staty_core\PageTemplateDummy', $source_file->getTemplateClass());

    }

    public function testStripExtension() {
        $this->assertEquals("hello.html", SourcePhpScript::stripExtension("hello.html.php"));
    }

}
