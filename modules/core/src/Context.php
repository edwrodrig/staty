<?php
declare(strict_types=1);

namespace edwrodrig\staty_core;

/**
 *
 * @see https://tools.ietf.org/html/rfc3986#section-4.2
 * @see https://stackoverflow.com/questions/3127223/what-do-you-call-a-url-path-without-a-host-name
 */
class Context
{
    /**
     * The target web path of the generation.
     *
     * It is the base path that is used in the web side. It is different from {@see Context::$target_root_path}
     * in the way that this one is displayed when the site is deployed (if this is the site)
     * @see Context::getUrl()
     * @var string
     */
    private string $absolute_path;

    /**
     * @var Page[]
     */
    private array $prepared_page_list = [];

    public function __construct(string $absolute_path = '') {
        $this->absolute_path = $absolute_path;
    }

    public function getAbsolutePath() : string {
        return $this->absolute_path;
    }

    public function getLang() : string {
        $locale = setlocale(LC_ALL, "0");
        return substr($locale,0, 5);
    }

    public function prepare(Page $page) : bool {
        if ( !$this->isPagePrepared($page) ) {
            $this->prepared_page_list[$page->getRelativeFilename()] = $page;
            $page->prepare();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns true if page exists. It is compared by their {@see Page::getRelativeFilename() relative filename}
     * @param Page $page
     * @return bool
     */
    protected function isPagePrepared(Page $page) : bool {
        $relative_filename = $page->getRelativeFilename();
        return isset($this->prepared_page_list[$relative_filename]);
    }

    /**
     * @return Page[]
     */
    public function getPreparedPageList() : array {
        return $this->prepared_page_list;
    }
}