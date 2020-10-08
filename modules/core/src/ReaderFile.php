<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use Generator;
use labo86\staty\ErrMsg;

class ReaderFile extends Reader
{
    private string $filename;
    private string $base_path;

    /**
     * ReaderFile constructor.
     * @param Context $context
     * @param string $filename
     * @param string $base_path
     * @throws ExceptionWithData
     */
    public function __construct(Context $context, string $filename, string $base_path = '') {
        parent::__construct($context);
        if ( !is_file($filename) )  throw new ExceptionWithData( ErrMsg::FILENAME_DOES_NOT_EXIST,
            [
                'filename' => $filename
            ]
        );
        $this->filename = $filename;
        $this->base_path = $base_path;


    }

    /**
     * @return Page
     * @throws ExceptionWithData
     */
    public function getPage() : Page {
        if ( SourcePhpScript::isPhp($this->filename) ) {
            $source = new SourceFile($this->filename);
            $relative_path = $this->getRelativePath(SourcePhpScript::stripExtension($this->filename));
            return new PagePhp($this->context, $relative_path, $source);
        } else {
            $source = new SourceFile($this->filename);
            $relative_path = $this->getRelativePath($this->filename);
            return new PageFile($source, $relative_path);
        }
    }

    protected function getRelativePath(string $filename) : string {
        $from = $this->base_path . '/';
        return Util::getRelativePath($from, $filename);
    }
}