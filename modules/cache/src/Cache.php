<?php
declare(strict_types=1);

namespace labo86\cache;

use DirectoryIterator;
use labo86\exception_with_data\ExceptionWithData;
use labo86\staty_core\Util;

class Cache
{
    private string $absolute_path;

    private string $relative_path;

    /**
     * @var Entry[]
     */
    private array $entry_map;

    /**
     * Cache constructor.
     * @param string $absolute_path
     * @param string $relative_path
     * @throws ExceptionWithData
     */
    public function __construct(string $absolute_path, string $relative_path = '__cache') {
        $this->absolute_path = $absolute_path;
        $this->relative_path = $relative_path;

        $directory = $this->absolute_path . '/' . $this->relative_path;
        if ( is_file($directory) )
            throw new ExceptionWithData("cache directory is a file",
            [ 'absolute_path' => $this->absolute_path, 'relative_path' => $this->relative_path ]);

        if ( !file_exists($directory) ) {
            if ( !mkdir($directory, 0777, true) ) {
                throw new ExceptionWithData("error creating cache directory",
                [ 'absolute_path' => $this->absolute_path, 'relative_path' => $this->relative_path ]);
            }
        }
        $this->entry_map = $this->getEntryMap();

    }

    /**
     * @return Entry[]
     */
    protected function getEntryMap() : array {
        $entry_map = [];
        $directory = $this->absolute_path . '/' . $this->relative_path;
        foreach ( Util::iterateFilesRecursively($directory) as $file ) {
            if ( $file->isDot() ) continue;
            $filename = $this->relative_path . '/' . $file->getFilename();
            $entry = Entry::createFromExistentFile($filename);
            $entry_map[$entry->getId()] = $entry;
        }
        return $entry_map;
    }

    /**
     * Obtiene una entrada de cache.
     * Si no existe entonces la crea.
     * @param string $id
     * @return Entry
     */
    public function getEntry(string $id) : Entry {
        if ( !isset($this->entry_map[$id]) )
            $this->entry_map[$id] =  new Entry($id);

        return $this->entry_map[$id];
    }
}