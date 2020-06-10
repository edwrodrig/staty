<?php
declare(strict_types=1);

namespace labo86\staty_core;

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

    /**
     * Prepara una página para su posterior generación.
     * Este método debe llamarse por cada página que se desea preparar para su generación.
     * La preparación es lo que esta definido por {@see Page::prepare()}.
     * Una misma página no puede ser preparada dos veces.
     * Se considera una misma página si tiene el mismo {@see Page::getId() id}.
     * La forma de identificar
     * @param Page $page
     * @return bool true si se preparó, false si ya había sido preparada
     */
    public function prepare(Page $page) : bool {
        if ( !$this->isPagePrepared($page) ) {
            $this->prepared_page_list[$page->getId()] = $page;
            $page->prepare();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Esta función se usa para determinar el retorno de la función {@see prepare()}.
     * Retorna true si se ha preparado la página. Recordar que se usa {@see Page::getRelativeFilename()}
     * como identificador de la página.
     *
     * Internamente se guarda un mapa con {@see Page::getId() como llave} y con eso se verifica
     * si una página a sido preparada o no.
     * @param Page $page
     * @return bool
     */
    protected function isPagePrepared(Page $page) : bool {
        $id = $page->getId();
        return isset($this->prepared_page_list[$id]);
    }

    /**
     * Obtiene la lista de páginas preparadas de paginas preparadas.
     * @return Page[]
     */
    public function getPreparedPageList() : array {
        return $this->prepared_page_list;
    }
}