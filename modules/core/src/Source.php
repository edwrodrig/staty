<?php
declare(strict_types=1);

namespace edwrodrig\staty_core;


abstract class Source
{
    abstract public function getContent() : string;
}