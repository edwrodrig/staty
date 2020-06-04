<?php
declare(strict_types=1);


namespace edwrodrig\staty_core;

use edwrodrig\exception_with_data\ExceptionWithData;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Object_;

class SourcePhpScript extends SourceFile
{

    public string $template_class = PageTemplate::class;

    protected function __construct(string $filename) {
        parent::__construct($filename);
        if ( $doc_block = $this->getDocBlock() ) {
            $this->getTemplateClassFromDocBlock($doc_block);
        }
    }

    /**
     * Get the first Documentation block of the file.
     *
     * In the first comment is where al annotations for templating would be. Other Doc comments are ignored.
     * It is used when in the template you want to retrieve further information for the template (Example: {@see TemplateJs})
     * @api
     * @return null|DocBlock
     */
    private function getDocBlock() : ?DocBlock {
        $tokens = token_get_all($this->getContent());
        foreach ($tokens as $token) {
            if ($token[0] !== T_COMMENT && $token[0] !== T_DOC_COMMENT)
                continue;

            $content = $token[1];
            $factory = DocBlockFactory::createInstance();
            return $factory->create($content);
        }
        return null;
    }

    /**
     * Parse template annotation
     *
     * Determine the template class of the processing
     * @param DocBlock $doc_block
     * @throws ExceptionWithData
     */
    private function getTemplateClassFromDocBlock(DocBlock $doc_block)
    {
        $template_class = '';

        $vars = $doc_block->getTagsByName('var');
        /** @var $var DocBlock\Tags\Var_ */
        foreach ($vars as $var) {

            if ($var->getVariableName() == 'template') {

                $type = $var->getType();
                if ( is_null($type) ) continue;
                if ( !$type instanceof Object_ ) break;
                $template_class = strval($type->getFqsen());
                $template_class = preg_replace("/^\\\\/", '', $template_class);
                break;
            }
        }

        if (empty($template_class) || $template_class == PageTemplate::class) {
            $this->template_class = PageTemplate::class;

        } else if (class_exists($template_class) && is_subclass_of($template_class, PageTemplate::class)) {
            $this->template_class = $template_class;

        } else {
            throw new ExceptionWithData( 'invalid template class',
                [
                    'template_class' => $template_class,
                    'filename' => $this->getFilename()
                ]
            );
        }
    }

    public function getTemplateClass() : string {
        return $this->template_class;
    }

    /**
     * Is the filename a php file.
     *
     * Just check by extension
     * @api
     * @param string $filename
     * @return bool
     */
    public static function isPhp(string $filename) : bool {
        $filename = basename($filename);
        return preg_match('/\.php$/', $filename) === 1;
    }

    public static function stripExtension(string $filename) : string {
        return preg_replace(
            '/\.php$/',
            '',
            $filename
        );
    }
}