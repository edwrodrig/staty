<?php
declare(strict_types=1);

namespace labo86\staty;


use labo86\exception_with_data\ExceptionWithData;

class SourceCached
{
    protected function __construct(Cache $cache, string $relativeFilename) {
        $this->cache = $cache;
        $this->id = $id;
    }

    public function getFilename() {
        return "some return";
    }

    public function cache() {
        $entry = $this->cache->getEntry($this->id);
        if ( $entry->isExpired() ) {

        }
        return true;
    }
}