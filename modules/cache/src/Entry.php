<?php
declare(strict_types=1);

namespace labo86\cache;

class Entry
{
    private string $id;
    private int $last_modification_time;

    private string $directory;

    public static function createFromExistentFile(string $current_filename, string $directory) : Entry {

        [$encoded_time, $id] = explode('_', $current_filename, 2);
        $time = intval(base_convert($encoded_time, 32, 10));

        $entry = new Entry($id, $directory);
        $entry->last_modification_time = $time;
        return $entry;
    }

    public function __construct(string $id, string $directory) {
        $this->id = $id;
        $this->directory = $directory;
    }

    public function getId() {
        return $this->id;
    }

    public function isExpired(int $modification_date) : bool {
        if ( !isset($this->last_modification_time) ) return true;
        return $this->last_modification_time < $modification_date;
    }

    public function getFilename(int $modification_date) : string {
        $current_date = max($this->last_modification_time ?? 0, $modification_date);
        return base_convert($current_date, 10, 32) . '_' . $this->id;
    }

}
