<?php
declare(strict_types=1);

namespace labo86\staty_core;

class PageString extends Page
{
    public function __construct(string $content, string $relative_filename) {
        parent::__construct(
            SourceString::createFromString($content),
            $relative_filename
        );
    }
}