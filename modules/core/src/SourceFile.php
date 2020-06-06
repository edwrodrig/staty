<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;

class SourceFile extends Source
{
    protected string $filename;

    /**
     * @param string $string_data
     * @param string $filename
     * @return static
     * @throws ExceptionWithData
     */
    public static function createFromString(string $string_data, string $filename) : self {
        @mkdir(dirname($filename), 0777, true);
        file_put_contents( $filename, $string_data);
        return static::createFromFilename($filename);
    }

    /**
     * @param string $filename
     * @return static
     * @throws ExceptionWithData
     */
    public static function createFromFilename(string $filename) : self {
        return new static($filename);
    }

    /**
     * SourceFile constructor.
     * @param string $filename
     * @throws ExceptionWithData
     */
    protected function __construct(string $filename) {
        if ( !file_exists($filename) ) throw new ExceptionWithData( 'source file does not exists',
            [
                'filename' => $filename
            ]
        );
        $this->filename = $filename;
    }

    public function getFilename() : string {
        return $this->filename;
    }

    public function getContent() : string {
        return file_get_contents($this->filename);
    }
}