<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use Generator;

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
        if ( !is_file($filename) )  throw new ExceptionWithData( 'filename does not exists',
            [
                'filename' => $filename
            ]
        );
        $this->filename = $filename;
        $this->base_path = $base_path;


    }

    /**
     * @return \labo86\staty_core\Generator|Page[]
     * @throws ExceptionWithData
     */
    public function readPages() : Generator  {
        if ( SourcePhpScript::isPhp($this->filename) ) {
            $source = SourcePhpScript::createFromFilename($this->filename);
            $template = $source->getTemplateClass();
            $relative_path = $this->getRelativePath(SourcePhpScript::stripExtension($this->filename));
            $page = new $template($this->context, $relative_path, $source);

            $this->context->prepare($page);
            yield $page;
        } else {
            $source = SourceFile::createFromFilename($this->filename);
            $relative_path = $this->getRelativePath($this->filename);
            $page = new PageFile($source, $relative_path);

            $this->context->prepare($page);
            yield $page;
        }
    }

    protected function getRelativePath(string $filename) : string {
        $from = $this->base_path . '/';
        return Util::getRelativePath($from, $filename);
    }
}