<?php
declare(strict_types=1);

namespace labo86\staty_core;


class PageInfo
{
    private Context $context;

    private string $relative_filename;

    private string $source_filename;

    public function __construct(Context $context, string $relative_filename, string $source_filename) {
        $this->context = $context;
        $this->relative_filename = $relative_filename;
        $this->source_filename = $source_filename;
    }

    /**
     * Obtiene el contexto de generación.
     * Con el se puede 
     * @return Context
     */
    public function getContext() : Context {
        return $this->context;
    }

    /**
     * Obtiene la ruta relativa final de la página que se va a generar.
     * Si se quiere obtener la ruta absoluta, obtenerla de {@see getContext()}
     * @return string
     */
    public function getRelativeFilename() : string {
        return $this->relative_filename;
    }

    /**
     * La ruta del archivo original.
     * Debería obtenerse lo mismo con __DIR__ y __FILE__
     * @return string
     */
    public function getSourceFilename() : string {
        return $this->source_filename;
    }
}