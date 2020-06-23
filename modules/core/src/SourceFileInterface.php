<?php
declare(strict_types=1);

namespace labo86\staty_core;


interface SourceFileInterface
{

    /**
     * Devuelve el nombre del archivo real.
     * Sin importar si es accesible
     * @return string
     */
    public function getFilename() : string;

    /**
     * Devuelve el contenido del archivo
     * @return string
     */
    public function getContent() : string;

    /**
     * Devuelve la fecha de la última modificación
     * Debe ser una unix timestamp entera
     * @see filemtime()
     * @return int
     */
    public function getModificationDate() : int;

    /**
     * Retorna el tipo mime del archivo
     * @see mime_content_type()
     * @return string
     */
    public function getMimeType() : string;
}