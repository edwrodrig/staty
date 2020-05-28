<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\util\Exception;
use edwrodrig\util\Util;
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
     * @throws Exception
     */
    public function __construct(Context $context, string $filename, string $base_path = '') {
        parent::__construct($context);
        if ( !is_file($filename) ) throw Exception::create([
            'message' => 'filename does not exists',
            'data' => [
                'filename' => $filename
            ]
        ]);
        $this->filename = $filename;
        $this->base_path = $base_path;


    }

    /**
     * @return Generator|Page[]
     * @throws Exception
     */
    public function read_pages() : Generator  {
        if ( SourcePhpScript::is_php($this->filename) ) {
            $source = SourcePhpScript::create_from_filename($this->filename);
            $template = $source->get_template_class();
            $relative_path = $this->get_relative_path(SourcePhpScript::strip_extension($this->filename));
            $page = new $template($this->context, $relative_path, $source);

            $this->context->prepare($page);
            yield $page;
        } else {
            $source = SourceFile::create_from_filename($this->filename);
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