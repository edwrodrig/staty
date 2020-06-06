<?php
declare(strict_types=1);

namespace labo86\staty_core;


abstract class Source
{
    abstract public function getContent() : string;
}