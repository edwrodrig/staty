<?php
declare(strict_types=1);

namespace edwrodrig\staty;


use edwrodrig\staty_core\SourceString;

class PageString extends Page
{
    public function __construct(string $content, string $relative_filename) {
        parent::__construct(
            SourceString::createFromString($content),
            $relative_filename
        );
    }
}