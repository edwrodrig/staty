<?php
declare(strict_types=1);

namespace labo86\cache;

class Entry
{
    private string $id;
    private int $last_modification_time;

    public static function createFromExistentFile(string $id) : Entry {

        $dirname = dirname($id);
        $basename = basename($id);

        $elements = explode('.', $basename, 3);
        $time_string = array_splice($elements, 1, 1)[0];

        $entry = new Entry($dirname . '/' . implode('.', $elements));
        $entry->last_modification_time = intval(base_convert($time_string, 32, 10));

        return $entry;
    }

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function isExpired(int $modification_date) : bool {
        if ( !isset($this->last_modification_time) ) return true;
        return $this->last_modification_time < $modification_date;
    }

    public function getFilename(int $modification_date) : string {

        $dirname = dirname($this->id);
        $basename = basename($this->id);

        $current_date = max($this->last_modification_time ?? 0, $modification_date);
        $elements = explode('.', $basename, 2);
        array_splice( $elements, 1, 0, base_convert($current_date, 10, 32) );
        return $dirname . '/' . implode('.', $elements);
    }


}
