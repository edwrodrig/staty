<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

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
    public function test_get_template_class_no_doc()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_template_class_doc_no_var()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @silent */', $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->getTemplateClass());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test_get_template_class_doc_var_no_template()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @var some $hello **/', $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->getTemplateClass());
    }

    public function test_get_template_class_doc_var_template_unknown()
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
    public function test_get_template_class_doc_var_template_known()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::createFromString('<?php /** @var test\edwrodrig\staty\DummyPageTemplate $template **/', $source_filename);
        $this->assertEquals('test\edwrodrig\staty\DummyPageTemplate', $source_file->getTemplateClass());

    }

    public function test_strip_extension() {
        $this->assertEquals("hello.html", SourcePhpScript::stripExtension("hello.html.php"));
    }

}
