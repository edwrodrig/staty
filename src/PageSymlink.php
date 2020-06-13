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
     * @param string $target
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
        if ( file_exists($filename) || is_link($filename) )
            unlink($filename);

        $from = $filename;
        $to = $this->source->getFilename();

        $target = Util::getRelativePath($from, $to);
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
}