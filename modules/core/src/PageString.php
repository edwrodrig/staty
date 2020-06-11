<?php
declare(strict_types=1);

namespace labo86\staty_core;

class PageString extends Page
{
    protected SourceString $source;

    protected int $modification_date;

    public function __construct(string $content, string $relative_filename) {
        parent::__construct($relative_filename);
        $this->source = SourceString::createFromString($content);
        $this->modification_date = $this->source->getModificationTime();

    }

    public function getContent() : string {
        return $this->source->getContent();
    }

    public function getModificationDate(): int
    {
        return $this->modification_date;
    }
}