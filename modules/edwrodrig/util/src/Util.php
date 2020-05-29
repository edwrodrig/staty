<?php
declare(strict_types=1);


namespace edwrodrig\util;

use FilesystemIterator;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class Util
{
    /**
     * @param callable $callable
     * @return false|string
     * @throws Throwable
     */
    public static function output_buffer_safe(callable $callable)
    {
        $level = ob_get_level();
        try {
            ob_start();
            $callable();
            return ob_get_clean();

        } catch (Throwable $exception) {
            while (ob_get_level() > $level) ob_get_clean();
            throw $exception;
        }
    }

    /**
     * @see https://stackoverflow.com/questions/2637945/getting-relative-path-from-absolute-path-in-php
     * @param string $from
     * @param string $to
     * @return string
     */
    public static function get_relative_path(string $from, string $to) : string
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
        $from = str_replace('\\', '/', $from);
        $to   = str_replace('\\', '/', $to);

        $from     = explode('/', $from);
        $to       = explode('/', $to);
        $relPath  = $to;

        foreach($from as $depth => $dir) {
            // find first non-matching dir
            if($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                }
            }
        }
        return implode('/', $relPath);
    }

    /**
     * @param string $directory_path
     * @return Generator|RecursiveDirectoryIterator[]
     */
    public static function iterate_files_recursively(string $directory_path) : Generator {
        $iterator = new RecursiveDirectoryIterator(
            $directory_path,
            FilesystemIterator::CURRENT_AS_SELF
        );

        /** @var $file RecursiveDirectoryIterator */
        foreach ( new RecursiveIteratorIterator($iterator) as $file ) {
            if ( !$file->isFile() ) continue;
            yield $file;
        }
    }
}