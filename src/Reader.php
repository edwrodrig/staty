<?php
declare(strict_types=1);


namespace edwrodrig\staty;


use Generator;

abstract class Reader
{
    protected Context $context;

    public function __construct(Context $context) {
        $this->context = $context;

    }

    /**
     * @return Generator|Page[]
     */
    abstract public function read_pages() : Generator;
}