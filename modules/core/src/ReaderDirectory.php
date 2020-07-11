<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use Generator;

class ReaderDirectory extends Reader
{
    private string $directory_path;

    /**
     * ReaderFile constructor.
     * @param Context $context
     * @param string $directory_path
     * @throws ExceptionWithData
     */
    public function __construct(Context $context, string $directory_path) {
        parent::__construct($context);
        if ( is_file($directory_path) )
            throw new ExceptionWithData(
    'directory path is a file', ['directory_path' => $directory_path ]);
        if ( !is_dir($directory_path) )
            throw new ExceptionWithData(
            'directory does not exists', [ 'directory_path' => $directory_path]);

        $this->directory_path = $directory_path;
    }

    /**
     * No se debe mezclar el funcionamiento del lector con la preparación en un contexto.
     * Hacer esto de manera manual.
     * @return Generator|Page[]
     * @throws ExceptionWithData
     * @deprecated
     */
    public function readPages() : Generator  {
        foreach ( $this->generatePages() as $page ) {
            $this->context->prepare($page);
            yield $page;
        }
    }

    /**
     * @return Generator|Page[]
     * @throws ExceptionWithData
     */
    public function generatePages() : Generator {
        foreach (Util::iterateFilesRecursively($this->directory_path) as $file ) {
            $reader = new ReaderFile($this->context, $file->getPathname(), $this->directory_path);
            yield from $reader->generatePages();
        }
    }

}