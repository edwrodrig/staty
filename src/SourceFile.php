<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\exception_with_data\ExceptionWithData;

class SourceFile extends Source
{
    protected string $filename;

    /**
     * @param string $string_data
     * @param string $filename
     * @return static
     * @throws ExceptionWithData
     */
    public static function create_from_string(string $string_data, string $filename) : self {
        @mkdir(dirname($filename), 0777, true);
        file_put_contents( $filename, $string_data);
        return static::create_from_filename($filename);
    }

    /**
     * @param string $filename
     * @return static
     * @throws ExceptionWithData
     */
    public static function create_from_filename(string $filename) : self {
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

    public function get_filename() : string {
        return $this->filename;
    }

    public function get_content() : string {
        return file_get_contents($this->filename);
    }
}