<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty_core\Context;
use labo86\staty_core\Page;
use labo86\staty_core\PagePhp;
use labo86\staty_core\SourceFile;
use labo86\staty_core\Util;
use Throwable;

/**
 * Class Block
 * Esta clase sirve para bloques html que tienen secciones de llenado intermedia
 * @package edwrodrig\mypage\site
 */
class Block
{
    protected PagePhp $page;
    protected array $section_map = [];
    private string $current_section_name;

    public function __construct(PagePhp $page) {
        $this->page = $page;
    }

    /**
     * Abre una nueva sección con un cierto nombre.
     * Si hay una sección abierta la cierra.
     * Internamente lo que hace es abrir un {@see ob_start() output buffer}
     * @param string $section_name
     */
    public function sectionBegin(string $section_name) {
        $this->sectionEnd();
        $this->current_section_name = $section_name;
        ob_start();
    }

    /**
     * Cierra una sección. Si no hay una sección abierta no hace nada
     * El cierre cierra el {@see ob_get_clean() output buffer} y lo guarda internamente
     */
    public function sectionEnd() {
        if ( isset($this->current_section_name) ) {
            $this->section_map[$this->current_section_name] = ob_get_clean();
            unset($this->current_section_name);
        }
    }

    /**
     * Obtiene el valor de una sección.
     * Si no tiene nada entonces devuelve vacío.
     * @param string $section_name
     * @return string
     */
    public function getSectionContent(string $section_name) : string {
        return $this->section_map[$section_name] ?? '';
    }

    /**
     * Obtiene una lista de nombre de secciones registradas hasta el momento.
     * @return array
     */
    public function getSectionNameList() : array {
        return array_keys($this->section_map);
    }

    /**
     * Esta función debe imprimir el bloque html
     * Es recomendable llamar a {@see sectionEnd()} al principio de esta función para cerrar las secciones abiertas.
     */
    public function html() {
        $this->sectionEnd();
        foreach ( $this->getSectionNameList() as $section_name ) {
            echo $this->getSectionContent($section_name);
        }
    }

    /**
     * Esta función sirve para crear páginas dentro de una página.
     * Lo que hace esta función es {@see Page::prepare() preparar} la página para generar una.
     *
     * Esta función devuelve la {@see getRelativePathFromThis() ruta} relativa de la nueva pagina con respecto al actual
     * @param Page $page
     * @param bool $cached Si esta página es cacheada o no
     * @return string the relative url of the new page
     * @throws ExceptionWithData
     */
    public function makePage(Page $page, bool $cached = false) : string {
        if ( $cached && $this->page->getContext()->hasCache() ) {
            $page = new PageCached($page, $this->page->getContext()->getCache());
        }
        $this->page->getContext()->prepare($page);
        return $this->getRelativeFilenameFromThis($page->getRelativeFilename());
    }

    /**
     * Esta función devuelve la ruta relativa de la nueva pagina con respecto al actual.
     * Esto se hace considerando {@see getRelativeFilename()} de ambas páginas.
     * @param string $relative_filename
     * @return string
     * @throws ExceptionWithData
     */
    public function getRelativeFilenameFromThis(string $relative_filename) : string {
        $from = $this->page->getContext()->getAbsolutePath() . '/' . $this->page->getRelativeFilename();
        $to = $this->page->getContext()->getAbsolutePath() . '/' . $relative_filename;
        return Util::getRelativePath($from, $to);
    }

    /**
     * El mismo comportamiento que {@see sprintf()}
     * pero si alguno de los elementos es nulo entonces devuelve un string vacio
     * @param string $str
     * @param mixed ...$args
     * @return string
     */
    public function sprintf(string $str, ...$args) : string {
        foreach ( $args as &$arg ) {
            if ( is_null($arg) ) return "";
            if ( is_string($arg))
                $arg = htmlentities($arg);
        }
        return sprintf($str, ...$args);
    }

    /**
     * Construye un objeto PagePhp.
     * Es útil para usar bloques en contextos donde el bloque no es llamado desde una página.
     * Se puede usar cuando se usan bloques para pruebas.
     * @return PagePhp
     */
    public static function thisPage() : PagePhp {
        return new PagePhp(new Context(), __FILE__, new SourceFile(__FILE__));
    }

    /**
     * Esta función registra una excepción que ocurre en la página.
     * Un bloque no debería nunca generar una excepción.
     * {@see PagePhp::registerException()}
     * @param Throwable $throwable
     */
    public function registerException(Throwable $throwable) {
        $this->page->registerException($throwable);
    }
}