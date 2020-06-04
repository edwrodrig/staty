<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\staty_core\Util;
use Throwable;

/**
 * Class PageTemplate
 * @package edwrodrig\staty
 * @property SourcePhpScript source
 */
class PageTemplate extends Page
{
    protected Context $context;


    public function __construct(Context $context, string $relative_filename, SourcePhpScript $php_script) {
        parent::__construct($php_script, $relative_filename);
        $this->context = $context;

    }

    public function get_context() : Context {
        return $this->context;
    }

    protected function get_source() : SourcePhpScript {
        return $this->source;
    }

    /**
     * @throws Throwable
     */
    public function get_content() : string {
        $template = $this;
        return Util::output_buffer_safe(function() use($template) {
            /** @noinspection PhpIncludeInspection */
            include $template->get_source()->get_filename();
        });
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function prepare() : bool {
        $this->get_content();
        return true;
    }

    /**
     * @param Page $page
     * @return string the relative url of the new page
     */
    public function make_page(Page $page) : string {
        $this->context->prepare($page);
        $from = $this->context->get_absolute_path() . '/' . $this->get_relative_filename();
        $to = $this->context->get_absolute_path() . '/' . $page->get_relative_filename();
        return Util::get_relative_path($from, $to);
    }
}