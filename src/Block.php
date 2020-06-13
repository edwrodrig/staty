<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\staty_core\Page;
use labo86\staty_core\PagePhp;
use labo86\staty_core\Util;

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
     * @return string the relative url of the new page
     */
    public function makePage(Page $page) : string {
        $this->page->getContext()->prepare($page);
        return $this->getRelativeFilenameFromThis($page->getRelativeFilename());
    }

    /**
     * Esta función devuelve la ruta relativa de la nueva pagina con respecto al actual.
     * Esto se hace considerando {@see getRelativeFilename()} de ambas páginas.
     * @param string $relative_filename
     * @return string
     */
    public function getRelativeFilenameFromThis(string $relative_filename) : string {
        $from = $this->page->getContext()->getAbsolutePath() . '/' . $this->page->getRelativeFilename();
        $to = $this->page->getContext()->getAbsolutePath() . '/' . $relative_filename;
        return Util::getRelativePath($from, $to);
    }
}