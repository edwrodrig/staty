<?php
declare(strict_types=1);

namespace test\edwrodrig\staty;

use edwrodrig\staty\Context;
use edwrodrig\staty\PageString;
use edwrodrig\staty\PageTemplate;
use edwrodrig\staty_core\SourcePhpScript;
use edwrodrig\exception_with_data\ExceptionWithData;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Throwable;

class PageTemplateTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp() : void {
        $this->root = vfsStream::setup();
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testGetContent()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "output", $source_file);
        $this->assertEquals("hello", $template->getContent());
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetContext()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "output", $source_file);
        $this->assertEquals($context, $template->getContext());
    }

    /**
     * @throws ExceptionWithData
     */
    public function test__construct()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "output", $source_file);
        $this->assertInstanceOf(PageTemplate::class, $template);
    }

    /**
     * @throws ExceptionWithData
     */
    public function testGetRelativeFilename()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "output", $source_file);
        $this->assertEquals('output', $template->getRelativeFilename());
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testPrepare()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context();
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "output", $source_file);
        $this->assertTrue($template->prepare());
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testMakePage()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context("path");
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "folder_1/file_1", $source_file);


        $page = new PageString("hello", "folder_2/file_2");
        $this->assertEquals("../folder_2/file_2", $template->makePage($page));
    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testMakePageInsideScript()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context("path");
        $source_file = SourcePhpScript::createFromString(
<<<'EOF'
<?php
use edwrodrig\staty\PageString;

$page = new PageString("hello", "folder_2/file_2");
$template->makePage($page);
EOF
        , $source_filename);
        $template = new PageTemplate($context, "folder_1/file_1", $source_file);
        $template->prepare();

        $page_list = $context->getPreparedPageList();
        $this->assertCount(1, $page_list);
        $this->assertArrayHasKey("folder_2/file_2", $page_list);
        $inside_file = $page_list["folder_2/file_2"];
        $this->assertEquals("hello", $inside_file->getContent());
        $this->assertEquals("folder_2/file_2", $inside_file->getRelativeFilename());

    }

    /**
     * @throws Throwable
     * @throws ExceptionWithData
     */
    public function testMakePagePrepare()
    {
        $path = $this->root->url();
        $source_filename = $path . "/file";

        $context = new Context("path");
        $source_file = SourcePhpScript::createFromString("hello", $source_filename);
        $template = new PageTemplate($context, "folder_1/file_1", $source_file);

        $page = new class("content", "folder_2/file_2") extends PageString {

            public bool $prepared = false;
            public string $change = "prepare_not_called";

            public function prepare() : bool {
                $this->prepared = true;
                $this->change = "prepare_called";
                return true;
            }
        };

        $this->assertFalse($page->prepared);
        $this->assertEquals("prepare_not_called", $page->change);

        $this->assertEquals("../folder_2/file_2", $template->makePage($page));
        $this->assertTrue($page->prepared);
        $this->assertEquals("prepare_called", $page->change);

        $page->change = "prepare_already_called";
        $this->assertEquals("../folder_2/file_2", $template->makePage($page));

        $this->assertTrue($page->prepared);
        $this->assertEquals("prepare_already_called", $page->change);
        $this->assertEquals("../folder_2/file_2", $template->makePage($page));
    }
}
