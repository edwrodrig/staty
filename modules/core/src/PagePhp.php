<?php
declare(strict_types=1);

namespace labo86\staty_core;

use Throwable;

/**
 * Class PagePhp
 * @package labo86\staty
 * @property SourcePhpScript source
 */
class PagePhp extends Page
{
    protected SourceFileInterface $source;
    protected Context $context;

    public array $metadata;

    /**
     * @var Throwable[]
     */
    private array $exceptions = [];

    public function __construct(Context $context, string $relative_filename, SourceFileInterface $source) {
        parent::__construct($relative_filename);
        $this->source = $source;
        $this->context = $context;

    }

    /**
     * @throws Throwable
     */
    public function getContent() : string {
        $page = $this;
        return Util::outputBufferSafe(function() use($page) {
            /** @noinspection PhpIncludeInspection */
            include $page->getSourceFilename();
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
     * Esta función esta hecha para ser llamada desde dentro del archivo php para asociar metadata a este archivo.
     * La idea es que sea un array asociativo estilo json.
     * La metadata es una forma de comunicar una página con el sistema generador.
     * @param array $metadata
     */
    public function prepareMetadata(array $metadata) {
        if ( !isset($this->metadata) ) $this->metadata = $metadata;
    }

    public function getMetadata() : array {
        return $this->metadata ?? [];
    }

    public function getContext() : Context {
        return $this->context;
    }

    public function getSourceFilename() : string {
        return $this->source->getFilename();
    }

    /**
     * Use esta función para registrar las excepciones que ocurren en una página.
     * Las páginas no debería lanzar excepciones por lo que todo lo que lancé una excepción debería ser registrado por esta función.
     * Es decir, en vez de
     * <code>
     * throw new Exception("some error");
     * </code>
     * use esto
     * <code>
     * $page->registerException(new Exception("some error"));
     * </code>
     *
     * @param Throwable $throwable
     */
    public function registerException(Throwable $throwable) {
        $this->exceptions[] = $throwable;
    }

    /**
     * Obtiene la lista de excepciones registradas en la página hasta el momento.
     * @see registerException()
     * @return array
     */
    public function getExceptionList() : array {
        return $this->exceptions;
    }
}