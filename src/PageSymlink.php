<?php
declare(strict_types=1);

namespace labo86\staty;

use labo86\exception_with_data\ExceptionWithData;
use labo86\staty_core\PageFile;
use labo86\staty_core\SourceFile;
use labo86\staty_core\Util;

/**
 * Class PageSymlink
 * Sirve para poner symlinks en el generador
 * @package labo86\staty
 */
class PageSymlink extends PageFile
{

    /**
     * PageSymlink constructor.
     * @param string $target Si es un symlink de directorio poner un / al final de target
     * @param string $link
     * @throws ExceptionWithData
     */
    public function __construct(string $target, string $link) {
        parent::__construct(new SourceFile($target), $link);
    }

    /**
     * @param string $filename
     * @throws ExceptionWithData
     */
    public function generate(string $filename) {

        $from = $filename;
        $to = $this->source->getFilename();

        $target = Util::getRelativePath($from, $to);
        // remover el ultimo slash si es que lo tiene, en caso contrario symlink falla
        $filename = rtrim($filename, '/');

        $this->cleanTarget($filename);

        if ( @symlink($target, $filename) === FALSE ) {
            throw new ExceptionWithData("error making symlink",
                [
                    'from' => $from,
                    'to' => $to,
                    'target' => $target,
                    'link' => $filename
                ]
            );
        }
    }

    /**
     * Remove an existent filename
     * @param string $filename
     * @throws ExceptionWithData
     */
    public function cleanTarget(string $filename) {
        if ( is_link($filename) ) {
            if ( !unlink($filename) ) {
                throw new ExceptionWithData("error unlinking symlink",
                    [
                        'filename' => $filename
                    ]
                );
            }
        }
        else if ( is_dir($filename)) {
            if ( !rmdir($filename) ) {
                throw new ExceptionWithData("error removing directory",
                    [
                        'filename' => $filename,
                        'hint' => 'quizás el directorio no está vacío'
                    ]
                );
            }
        }
        else if ( file_exists($filename) ) {
            if ( !unlink($filename) ) {
                throw new ExceptionWithData("error unlinking existent file",
                    [
                        'filename' => $filename
                    ]
                );
            }
        }
    }
}