<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\util\Exception;
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
     * @throws Exception
     */
    public function test_get_template_class_no_doc()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::create_from_string("hello", $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->get_template_class());
    }

    /**
     * @throws Exception
     */
    public function test_get_template_class_doc_no_var()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::create_from_string('<?php /** @silent */', $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->get_template_class());
    }

    /**
     * @throws Exception
     */
    public function test_get_template_class_doc_var_no_template()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::create_from_string('<?php /** @var some $hello **/', $source_filename);
        $this->assertEquals("edwrodrig\staty\PageTemplate", $source_file->get_template_class());
    }

    public function test_get_template_class_doc_var_template_unknown()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";
        try {

            SourcePhpScript::create_from_string('<?php /** @var invalid $template **/', $source_filename);
            $this->fail("Should throw");
        } catch ( Exception $exception ) {
            $this->assertEquals([
                'message' => 'invalid template class',
                'data' => [
                    'filename' => $source_filename,
                    'template_class' => 'invalid'
                ]
            ], $exception->get_structured_data());
        }
    }

    /**
     * @throws Exception
     */
    public function test_get_template_class_doc_var_template_known()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $source_file = SourcePhpScript::create_from_string('<?php /** @var edwrodrig\staty\test\DummyPageTemplate $template **/', $source_filename);
        $this->assertEquals('edwrodrig\staty\test\DummyPageTemplate', $source_file->get_template_class());

    }

    public function test_strip_extension() {
        $this->assertEquals("hello.html", SourcePhpScript::strip_extension("hello.html.php"));
    }

}
