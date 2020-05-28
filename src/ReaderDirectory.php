<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\util\Exception;
use edwrodrig\util\Util;
use Generator;

class ReaderDirectory extends Reader
{
    private string $directory_path;

    /**
     * ReaderFile constructor.
     * @param Context $context
     * @param string $directory_path
     * @throws Exception
     */
    public function __construct(Context $context, string $directory_path) {
        parent::__construct($context);
        if ( is_file($directory_path) ) throw Exception::create([
           'message' => 'directory path is a file',
           'data' => [
                'directory_path' => $directory_path
           ]

        ]);
        if ( !is_dir($directory_path) ) throw Exception::create([
            'message' => 'directory does not exists',
            'data' => [
                'directory_path' => $directory_path
            ]
        ]);
        $this->directory_path = $directory_path;
    }

    /**
     * @return Generator|Page[]
     * @throws Exception
     */
    public function read_pages() : Generator  {
        foreach ( Util::iterate_files_recursively($this->directory_path) as $file ) {
            $reader = new ReaderFile($this->context, $file->getPathname(), $this->directory_path);
            yield from $reader->read_pages();
        }
    }

}