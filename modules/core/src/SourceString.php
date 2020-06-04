<?php
declare(strict_types=1);

namespace edwrodrig\staty_core;

class SourceString extends Source
{
    protected string $string_data;

    /**
     * @param string $string_data
     * @return static
     */
    public static function createFromString(string $string_data) : self {
        $source = new SourceString;
        $source->string_data = $string_data;
        return $source;
    }


    public function getContent() : string {
        return $this->string_data;
    }
}