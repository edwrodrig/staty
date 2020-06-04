<?php
declare(strict_types=1);


namespace edwrodrig\staty_core;


abstract class Reader
{
    protected Context $context;

    public function __construct(Context $context) {
        $this->context = $context;

    }

}