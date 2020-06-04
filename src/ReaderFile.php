<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\exception_with_data\ExceptionWithData;
use edwrodrig\staty_core\SourceFile;
use edwrodrig\staty_core\SourcePhpScript;
use edwrodrig\staty_core\Util;
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
     * @return Generator|Page[]
     * @throws ExceptionWithData
     */
    public function read_pages() : Generator  {
        if ( SourcePhpScript::isPhp($this->filename) ) {
            $source = SourcePhpScript::createFromFilename($this->filename);
            $template = $source->getTemplateClass();
            $relative_path = $this->get_relative_path(SourcePhpScript::stripExtension($this->filename));
            $page = new $template($this->context, $relative_path, $source);

            $this->context->prepare($page);
            yield $page;
        } else {
            $source = SourceFile::createFromFilename($this->filename);
            $relative_path = $this->get_relative_path($this->filename);
            $page = new Page($source, $relative_path);

            $this->context->prepare($page);
            yield $page;
        }
    }

    public function get_relative_path(string $filename) : string {
        $from = $this->base_path;
        return Util::get_relative_path($from, $filename);
    }
}