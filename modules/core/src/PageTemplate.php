<?php
declare(strict_types=1);

namespace edwrodrig\staty_core;

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

    public function getContext() : Context {
        return $this->context;
    }

    protected function getSource() : SourcePhpScript {
        return $this->source;
    }

    /**
     * @throws Throwable
     */
    public function getContent() : string {
        $template = $this;
        return Util::outputBufferSafe(function() use($template) {
            /** @noinspection PhpIncludeInspection */
            include $template->getSource()->getFilename();
        });
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function prepare() : bool {
        $this->getContent();
        return true;
    }

    /**
     * @param Page $page
     * @return string the relative url of the new page
     */
    public function makePage(Page $page) : string {
        $this->context->prepare($page);
        $from = $this->context->getAbsolutePath() . '/' . $this->getRelativeFilename();
        $to = $this->context->getAbsolutePath() . '/' . $page->getRelativeFilename();
        return Util::getRelativePath($from, $to);
    }
}