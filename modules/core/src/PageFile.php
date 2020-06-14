<?php
declare(strict_types=1);

namespace labo86\staty_core;

/**
 * Class Page
 *
 * This class represent a page that going to be generated.
 * One page is one file
 * @package labo86\staty
 */
class PageFile extends Page
{
    protected SourceFile $source;
    protected string $relative_filename;

    public function __construct(SourceFile $source, string $relative_filename) {
        parent::__construct($relative_filename);
        $this->source = $source;
    }

    /**
     * Retorna los contenidos de la página.
     * Esta función depende mucho del contexto.
     * Si es un archivo entonces es su contenido. Si es un script es su salida.
     * Si es un string es su valor.
     * @return string
     */
    public function getContent() : string {
        return $this->source->getContent();
    }

    /**
     * Genera la página.
     * Vuelva el contenido de la página hacia un archivo.
     * Por defecto simplemente vuelca a un archivo.
     * Pero se puede sobrecargar este método para que tenga otro comportamiento.
     * Por ejemplo usar {@see copy()} es vez de {@see file_put_contents()}
     * @param string $filename
     */
    public function generate(string $filename) {
        copy($this->source->getFilename(), $filename);
    }

    public function getModificationDate(): int {
        return $this->source->getModificationDate();
    }

}