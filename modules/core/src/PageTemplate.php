<?php
declare(strict_types=1);

namespace labo86\staty_core;

use Throwable;

/**
 * Class PageTemplate
 * @package labo86\staty
 * @property SourcePhpScript source
 */
class PageTemplate extends Page
{
    protected SourcePhpScript $source;
    protected Context $context;


    public function __construct(Context $context, string $relative_filename, SourcePhpScript $php_script) {
        parent::__construct($relative_filename);
        $this->source = $php_script;
        $this->context = $context;

    }

    public function getContext() : Context {
        return $this->context;
    }

    /**
     * @throws Throwable
     */
    public function getContent() : string {
        $template = $this;
        $source = $this->source;
        return Util::outputBufferSafe(function() use($template, $source) {
            /** @noinspection PhpIncludeInspection */
            include $source->getFilename();
        });
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function prepare() : bool {
        $this->getContent();
        return true;
    }

    /**
     * Esta función sirve para crear páginas dentro de una página.
     * Lo que hace esta función es {@see Page::prepare() preparar} la página para generar una.
     *
     * Esta función devuelve la ruta relativa de la nueva pagina con respecto al actual.
     * Esto se hace considerando {@see getRelativeFilename()} de ambas páginas.
     * @param Page $page
     * @return string the relative url of the new page
     */
    public function makePage(Page $page) : string {
        $this->context->prepare($page);
        $from = $this->context->getAbsolutePath() . '/' . $this->getRelativeFilename();
        $to = $this->context->getAbsolutePath() . '/' . $page->getRelativeFilename();
        return Util::getRelativePath($from, $to);
    }
}