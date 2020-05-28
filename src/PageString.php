<?php
declare(strict_types=1);

namespace edwrodrig\staty;


class PageString extends Page
{
    public function __construct(string $content, string $relative_filename) {
        parent::__construct(
            SourceString::create_from_string($content),
            $relative_filename
        );
    }
}