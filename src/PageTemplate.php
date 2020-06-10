<?php
declare(strict_types=1);

namespace labo86\staty;


class PageTemplate extends \labo86\staty_core\PageTemplate
{

    public function url(string $relative_path) : string {
        return $this->getContext()->getAbsolutePath() . '/'. $relative_path;
    }
}