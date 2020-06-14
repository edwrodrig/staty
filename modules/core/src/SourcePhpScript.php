<?php
declare(strict_types=1);


namespace labo86\staty_core;

class SourcePhpScript extends SourceFileTemp
{
    /**
     * Is the filename a php file.
     *
     * Just check by extension
     * @api
     * @param string $filename
     * @return bool
     */
    public static function isPhp(string $filename) : bool {
        $filename = basename($filename);
        return preg_match('/\.php$/', $filename) === 1;
    }

    public static function stripExtension(string $filename) : string {
        return preg_replace(
            '/\.php$/',
            '',
            $filename
        );
    }
}