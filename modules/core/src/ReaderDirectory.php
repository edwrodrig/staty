<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;
use Generator;
use Throwable;

class ReaderDirectory extends Reader
{
    private string $directory_path;

    private array $exception_list = [];

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
     * @return Generator|(Page|null)[]
     * @deprecated
     */
    public function readPages() : Generator  {
        foreach ($this->iteratePages() as $page ) {
            if ( !is_null($page) )
                $this->context->prepare($page);
            yield $page;
        }
    }

    public function getExceptionList() : array {
        return $this->exception_list;
    }

    /**
     * Esta función genera lás páginas de este directorio. Si una página no puede ser generado y genera una excepción se van guardando en una lista de excepciones interna.
     * Esa lista se puede acceder con {@see getExceptionList()}
     * @return Generator|(Page|null)[]
     */
    public function iteratePages() : Generator {
        $this->exception_list = [];
        foreach (Util::iterateFilesRecursively($this->directory_path) as $file ) {
            try {
                $reader = new ReaderFile($this->context, $file->getPathname(), $this->directory_path);
                yield $reader->getPage();
            } catch ( Throwable $exception ) {
                $this->exception_list[] = $exception;
                yield null;
            }
        }

    }

}