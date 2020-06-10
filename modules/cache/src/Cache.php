<?php
declare(strict_types=1);

namespace labo86\cache;

use DirectoryIterator;
use labo86\exception_with_data\ExceptionWithData;

class Cache
{
    private string $directory;

    private array $used_entry_id_set = [];

    /**
     * @var Entry[]
     */
    private array $entry_map;

    /**
     * Cache constructor.
     * @param string $directory
     * @throws ExceptionWithData
     */
    public function __construct(string $directory) {
        $this->directory = $directory;
        if ( is_file($this->directory) )
            throw new ExceptionWithData("cache directory is a file",
            [ 'directory' => $directory ]);

        if ( !file_exists($this->directory) ) {
            if ( !mkdir($this->directory, 0777, true) ) {
                throw new ExceptionWithData("error creating cache directory",
                [ 'directory' => $this->directory]);
            }
        }
        $this->entry_map = $this->getEntryMap();

    }

    /**
     * @return Entry[]
     */
    protected function getEntryMap() : array {
        $entry_map = [];
        foreach ( new DirectoryIterator($this->directory) as $file ) {
            if ( $file->isDot() ) continue;
            $filename = $file->getBasename();
            $entry = Entry::createFromExistentFile($filename, $this->directory);
            $entry_map[$entry->getId()] = $entry;
        }
        return $entry_map;
    }

    public function getEntry(string $id) : Entry {
        $this->used_entry_id_set[$id] = true;
        if ( !isset($this->entry_map[$id]) )
            $this->entry_map[$id] =  new Entry($id, $this->directory);

        return $this->entry_map[$id];
    }

    public function clearUnusedEntries() : array {
        $cleared_entry_list = [];
        foreach ( $this->entry_map as $id => $entry ) {
            if ( isset($this->used_entry_id_set[$id]) )
                continue;
            $cleared_entry_list[] = $id;
            $entry->clear();
        }
        return $cleared_entry_list;
    }
}