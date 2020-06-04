<?php
declare(strict_types=1);

namespace edwrodrig\staty_core;

/**
 * Class Page
 *
 * This class represent a page that going to be generated.
 * One page is one file
 * @package edwrodrig\staty
 */
class Page
{
    protected Source $source;
    protected string $relative_filename;

    public function __construct(Source $source, string $relative_filename) {
        $this->source = $source;
        $this->relative_filename = $relative_filename;
    }

    /**
     * Get the relative filename where the page is going to be generated.
     * It is useful to
     * @return string
     */
    public function getRelativeFilename(): string
    {
        return $this->relative_filename;
    }

    /**
     * Return the contents of the page to be generated
     * @return string
     */
    public function getContent() : string {
        return $this->source->getContent();
    }

    public function prepare() : bool {
        return true;
    }

}