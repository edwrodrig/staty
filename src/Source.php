<?php
declare(strict_types=1);

namespace edwrodrig\staty;


abstract class Source
{
    abstract public function get_content() : string;
}