<?php
declare(strict_types=1);

namespace labo86\cache;

class Entry
{
    private string $id;

    private string $directory;

    public function __construct(string $id, string $directory) {
        $this->id = $id;
        $this->directory = $directory;
    }

    public function getId() {
        return $this->id;
    }

    public function getFilename() : string {
        return $this->directory . '/' . $this->id;

    }

    public function getModificationTime() : int {
        if ( file_exists($this->getFilename()) ) {
            return filemtime($this->getFilename());
        } else {
            return 0;
        }
    }
}
