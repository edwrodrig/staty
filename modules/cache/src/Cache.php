<?php
declare(strict_types=1);

namespace labo86\cache;

use DirectoryIterator;
use labo86\exception_with_data\ExceptionWithData;

class Cache
{
    private string $directory;

    private array $usedEntryList = [];

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

    }

    public function getEntry(string $id) : Entry {
        $this->usedEntryList[] = $id;
        return new Entry($id, $this->directory);
    }

    public function clearUnusedEntries() : array {
        $cleared_entry_list = [];
        foreach ( new DirectoryIterator($this->directory) as $file ) {
            if ( $file->isDot() ) continue;
            $id = $file->getBasename();
            if ( in_array($id, $this->usedEntryList ) )
                continue;
            else {
                $cleared_entry_list[] = $id;
                unlink($this->getEntry($id)->getFilename());
            }
        }
        return $cleared_entry_list;
    }
}