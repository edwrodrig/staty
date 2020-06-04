<?php
declare(strict_types=1);

namespace test\edwrodrig\staty_core;

use edwrodrig\staty_core\SourcePhpScript;
use edwrodrig\exception_with_data\ExceptionWithData;
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
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $this->assertEquals("edwrodrig\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocNoVar()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @silent */', $source_filename);
        $this->assertEquals("edwrodrig\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocVarNoTemplate()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @var some $hello **/', $source_filename);
        $this->assertEquals("edwrodrig\staty_core\PageTemplate", $source_file->getTemplateClass());
    }

    public function testGetTemplateClassDocVarTemplateUnknown()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        try {

            SourcePhpScript::createFromString('<?php /** @var invalid $template **/', $source_filename);
            $this->fail("Should throw");
        } catch ( ExceptionWithData $exception ) {

            $this->assertEquals('invalid template class', $exception->getMessage());
            $this->assertEquals([
                    'filename' => $source_filename,
                    'template_class' => 'invalid'
                ], $exception->getData());
        }
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetTemplateClassDocVarTemplateKnown()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @var edwrodrig\staty_core\PageTemplateDummy $template **/', $source_filename);
        $this->assertEquals('edwrodrig\staty_core\PageTemplateDummy', $source_file->getTemplateClass());

    }

    public function testStripExtension() {
        $this->assertEquals("hello.html", SourcePhpScript::stripExtension("hello.html.php"));
    }

}
