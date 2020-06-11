<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\cache\Cache;
use labo86\staty_core\Page;
use labo86\staty_core\SourceFile;
use Throwable;

/**
 * Class PageTemplate
 * @package labo86\staty
 */
class PageCached extends Page
{
    protected SourceFile $source;
    protected Cache $cache;
    protected string $cache_filename;

    protected Page $page;

    protected string $id;

    public function __construct(Page $page, Cache $cache) {
        parent::__construct($page->getRelativeFilename());

        // el identificador de la página cacheada es la ruta relativa de la original
        $this->id = $page->getRelativeFilename();

        /**
         * obtenemos la entrada de cache del archivo
         * QUE PASA SI EL CACHE ES UNA ESTRUCTURA DE DIRECTORIO?
         */
        $entry = $cache->getEntry(basename($page->getId()));

        // obtenemos el nombre que corresponde
        $this->cache_filename = $entry->getFilename($page->getModificationDate());

        /**
         * el nuevo relative name de esta pagina sera la entrada de cache.
         * de esta forma aparecerá la URL generada en las páginas web
         */
        $this->relative_filename = 'cache/' . $this->cache_filename;

        /** si la entrada no está expirada
         * OJO: Notar que las página nunca llamarán directamente sus métodos {@see page::generate() }
         * Solo se generarán mediante el método {@see PageCached::generate()} de esta clase.
         * La razón es porque normalmente generate está concebido a ser llamado por {@see Generator::generate()}.
         * donde se construye el path real de destino del archivo. Sin embargo este no es el caso dado que los archivos se tienen
         * que generar en el cache directamente, que se debe encontrar fuera de la carpeta de generación. Dejarlo adentro no tiene sentido.
         * ¿Puede el cache estar incorporado en totalidad?
         */
        if ( $entry->isExpired($page->getModificationDate()) ) {
            $this->page = $page;
            $page->setRelativeFilename($this->relative_filename);
        }

    }

    public function isExpired() : bool {
        return isset($this->page);
    }

    /**
     * En una cache entry no sirve el relative filename como Id.
     * Porque si se usa la técnica de cache busting entonces a través del tiempo existen muchos relative filenames que comparten el mismo id.
     * @return string
     */
    public function getId() : string {
        return $this->id;
    }

    public function prepare() : bool {
        if ( $this->isExpired() )
            return $this->page->prepare();
        else
            return true;
    }

    /**
     * @throws Throwable
     */
    public function getContent() : string {
        if ( $this->isExpired() )
            return $this->page->getContent();
        else
            return "";
    }

    /**
     * Se traspasa.
     * @param string $filename Totalmente ignorado
     */
    public function generate(string $filename) {
        if ( $this->isExpired() )
            $this->page->generate($filename);
    }
}