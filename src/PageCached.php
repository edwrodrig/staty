<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\cache\Cache;
use labo86\staty_core\Page;
use labo86\staty_core\PageFile;
use labo86\staty_core\SourceFile;
use Throwable;

/**
 * Class PageTemplate
 * @package labo86\staty
 * @property SourcePhpScript source
 */
class PageCached extends Page
{
    protected SourceFile $source;
    protected Cache $cache;

    protected Page $page;

    public function __construct(Page $page, Cache $cache) {
        parent::__construct($page->getRelativeFilename());
        $this->cache = $cache;
        $this->page = $page;

    }

    /**
     * @throws Throwable
     */
    public function getContent() : string {
        return $this->page->getContent();
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function prepare() : bool {
        $entry = $this->cache->getEntry($this->page->getRelativeFilename());
        $filename = $entry->getFilename($this->page->getModificationDate());
        if ( !$entry->isExpired($this->page->getModificationDate()) ) {
            $this->page = new PageFile(SourceFile::createFromFilename($filename), $this->page->getRelativeFilename());
        }
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