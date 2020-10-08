<?php
declare(strict_types=1);


namespace labo86\staty_core;

use FilesystemIterator;
use Generator;
use labo86\exception_with_data\ExceptionWithData;
use labo86\staty\ErrMsg;
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
    public static function outputBufferSafe(callable $callable)
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
     * Obtiene una ruta normalizada de un directorio. Eso es eliminando . y .. cuando lo permitan.
     * Los . y directorios vacíos solo se eliminan si no son el primero y el último
     * @param string $path
     * @return string
     */
    public static function getNormalizedPath(string $path) {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        /** se separa por el separador de directorio */
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        /** se filtran los . */
        $last_index = count($parts) - 1;
        $parts = array_filter($parts, function(string $part, int $index) use ($last_index) {
            if ( $index == 0 ) return true;
            if ( $index == $last_index ) return true;
            if ( $part == "" ) return false;
            return $part != '.' ;

        }, ARRAY_FILTER_USE_BOTH);


        $backwards = 0; /** este contador marca cuantas veces tenemos que ir más atrás de lo conocido **/
        $dir_stack = []; /** pila de directorios */

        /** usamos un procesamiento tipo pila para resolver los directorios **/
        foreach ($parts as $part) {
            /** si es un retroceso **/
            if ('..' == $part) {

                if ( empty($dir_stack) ) $backwards++; /** cuando la pila esta vacía igual necesitamos marcar que iremos aún más atrás **/
                else array_pop($dir_stack); /** si la pila tiene elementos entonces sacamos uno **/

            /** si es un nombre normal entonces lo agregamos a la pila **/
            } else {
                $dir_stack[] = $part;
            }
        }

        array_unshift($dir_stack, ...array_fill ( 0, $backwards, ".." ));

        /** Juntamos los elementos de la pila y agregamos una raíz en caso de ser una ruta absoluta. */
        return implode(DIRECTORY_SEPARATOR, $dir_stack);
    }

    /**
     * Retorna la ruta de un archivo con respecto a otro.
     * TENER EN CUENTA CIERTAS CONSIDERACIONES:
     * Si se quiere que el from sea considerado como directorio entonces DEBE terminar en /.
     * En caso contrario se considerar como archivo y se tomará solo la ruta para calcular
     * la ruta.
     * Ejemplo:
     *  - de path/a/ => path/b.
     *
     *    Como el origin termina con / entonces devuelve ../b.
     *  - de path/a => path/b
     *
     *    Como no termina en / se considera archivo entonces devuelve b
     * @see https://stackoverflow.com/questions/2637945/getting-relative-path-from-absolute-path-in-php
     * @param string $from
     * @param string $to
     * @return string
     */
    public static function getRelativePath(string $from, string $to) : string
    {

        /** normalizando directorios */
        $from = Util::getNormalizedPath($from);
        $to   = Util::getNormalizedPath($to);

        $first_from = $from[0] ?? '';
        $first_to   = $to[0] ?? '';
        // se usa xor porque solo se debe lanzar excepción cuando uno de los dos es root pero no ambos
        if ( ($first_from == '/') xor ($first_to == '/') ) throw new ExceptionWithData(
            ErrMsg::CANNOT_COMPARE_ABSOLUTE_AND_RELATIVE_PATH,
            [
                'from' => $from,
                'to' => $to
            ]
        );


        $from     = explode('/', $from);
        $to       = explode('/', $to);

        array_pop($from);

        /**
         * Este for each se detiene cuando encuentra la primera diferencia de directorios
         * y después se ejecuta lo que está en el else
         */
        $difference_start = 0;
        foreach($from as $depth => $dir) {
            if ($dir !== $to[$depth]) break;
            $difference_start ++;
        }

        $backward_moves = count($from) - $difference_start;
        $forward_directories = array_slice($to, $difference_start);

        return implode('/', [
            ...array_fill(0, $backward_moves, '..'),
            ...$forward_directories
        ]);
    }

    /**
     * Itera sobre los archivos de un directorio.
     * Cada elemento ver los métodos de {@see DirectoryIterator}
     * @param string $directory_path
     * @return Generator|RecursiveDirectoryIterator[]
     */
    public static function iterateFilesRecursively(string $directory_path) : Generator {
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