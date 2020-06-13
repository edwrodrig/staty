<?php
declare(strict_types=1);

namespace labo86\staty_core;


use labo86\exception_with_data\ExceptionWithData;

class SourceFileTemp extends SourceFile
{

    protected Source $source;

    public static function createFromFilename(string $filename) : SourceFile {
        return new static($filename);
    }

    /**
     * @param string $content
     * @return SourceFileTemp
     * @throws ExceptionWithData
     */
    public static function createFromString(string $content) : SourceFileTemp {
        $filename = tempnam(sys_get_temp_dir(), 'staty_temp');
        file_put_contents($filename, $content);
        $source = new SourceFileTemp($filename);
        $source->source = new static($filename);
        return $source;
    }

    public function __destruct() {
        if ( file_exists($this->filename) )
            unlink($this->filename);
    }


    /**
     * Conserva la fecha de modificación de la fuente original
     * @return int
     */
    public function getModificationDate() : int {
        return $this->source->getModificationTime();
    }
}