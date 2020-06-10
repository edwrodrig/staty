<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\cache\Cache;
use labo86\staty_core\Page;
use Throwable;

/**
 * Class PageTemplate
 * @package labo86\staty
 * @property SourcePhpScript source
 */
class PageCached extends Page
{
    protected Cache $cache;

    public function __construct(string $relative_filename, Source $source) {
        parent::__construct($source, $relative_filename);
        $this->cache = $cache;

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
        $entry = $this->cache->getEntry($this->getRelativeFilename());
        if ( $entry->getModificationTime() < $this->getModificatioName() ) {

        }
        return true;
    }

    public function generate() {

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