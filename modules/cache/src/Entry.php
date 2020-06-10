<?php
declare(strict_types=1);

namespace labo86\cache;

class Entry
{
    private string $id;
    private string $current_filename;
    private string $new_filename;

    private string $directory;

    public function createFromExistentFile(string $current_filename, string $directory) : Entry {

        $id = explode('_', $current_filename, 2)[1];
        $entry = new Entry($id, $directory);
        $entry->current_filename = $current_filename;
        return $entry;
    }

    public function __construct(string $id, string $directory) {
        $this->id = $id;
        $this->directory = $directory;
    }

    public function getId() {
        return $this->id;
    }

    protected function getCurrentFilename() : string {
        return $this->directory . '/' . $this->current_filename;
    }

    protected function getNewFilename() : string {
        if ( !isset($this->new_filename) )
            $this->new_filename = uniqid() . "_" . $this->id;
        return $this->directory . '/' . $this->new_filename;
    }

    public function isExpired(int $modification_date) : bool {
        if ( !isset($this->current_filename) ) return true;
        if ( !file_exists($this->getCurrentFilename()) ) return true;
        if ( filemtime($this->getCurrentFilename()) < $modification_date ) {
            unlink($this->getCurrentFilename());
            unset($this->current_filename);
            return true;
        }
        return false;
    }

    public function getFilename(int $modification_date) : string {
        if ( $this->isExpired($modification_date) )
            return $this->getNewFilename();
        else
            return $this->getCurrentFilename();
    }

    public function clear() {
        $filename = $this->current_filename ?? $this->new_filename ?? '';
        $filename = $this->directory . '/' . $filename;
        if ( file_exists($filename) )
            unlink($filename);

    }
}
