<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\ErrMsg;

class SourceFile extends Source implements SourceFileInterface
{
    protected string $filename;

    /**
     * @param string $filename
     * @return static
     * @throws ExceptionWithData
     * @deprecated
     */
    public static function createFromFilename(string $filename) : self {
        return new static($filename);
    }

    /**
     * SourceFile constructor.
     * @param string $filename
     * @throws ExceptionWithData
     */
    public function __construct(string $filename) {
        if ( !file_exists($filename) ) throw new ExceptionWithData( ErrMsg::SOURCE_FILE_DOES_NOT_EXIST,
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

    public function getModificationDate() : int {
        return filemtime($this->filename);
    }

    public function getMimeType(): string
    {
        return mime_content_type($this->filename);
    }
}