<?php
declare(strict_types=1);

namespace edwrodrig\staty;

class SourceString extends Source
{
    protected string $string_data;

    /**
     * @param string $string_data
     * @return static
     */
    public static function create_from_string(string $string_data) : self {
        $source = new SourceString;
        $source->string_data = $string_data;
        return $source;
    }


    public function get_content() : string {
        return $this->string_data;
    }
}