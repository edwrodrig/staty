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
abstract class Page
{
    protected string $relative_filename;

    public function __construct(string $relative_filename) {
        $this->relative_filename = $relative_filename;
    }

    /**
     * Get the relative filename where the page is going to be generated.
     * It is useful to
     * @return string
     */
    public function getRelativeFilename(): string
    {
        return $this->relative_filename;
    }

    /**
     * Retorna los contenidos de la página.
     * Esta función depende mucho del contexto.
     * Si es un archivo entonces es su contenido. Si es un script es su salidad.
     * Si es un string es su valor.
     * @return string
     */
    abstract public function getContent() : string;

    /**
     * Esta función prepara la página para su generación.
     * Considerar que el proceso completo está compuesto de dos fases. Una de preparación y otra de generación.
     * En la de generación debé ir el procesamiento pesado como conversión de imágenes, cómputo, etc.
     * Este es solamente para obtener la información previa necesaria para la generación.
     * @return bool
     */
    public function prepare() : bool {
        return true;
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
        file_put_contents($filename, $this->getContent());
    }

}