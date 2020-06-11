<?php
declare(strict_types=1);

namespace labo86\staty_core;

use labo86\exception_with_data\ExceptionWithData;

class Generator
{
    /**
     * @var Page[]
     */
    protected array $page_list = [];

    protected string $output_directory_path = '';

    public function __construct(string $output_directory_path) {
        $this->output_directory_path = $output_directory_path;
    }

    /**
     * @param Page[] $page_list
     */
    public function setPageList(array $page_list) : void {
        $this->page_list = $page_list;
    }

    /**
     * @param string $relative_filename
     * @return string
     * @throws ExceptionWithData
     */
    public function prepareOutputFilename(string $relative_filename) : string {
        $filename = $this->output_directory_path . '/' . $relative_filename;
        $directory_path = dirname($filename);
        if ( !file_exists($directory_path) )
            mkdir($directory_path, 0777, true);

        if ( !is_dir($directory_path) )
            throw new ExceptionWithData('target directory is not a directory',
                [
                    'relative_filename' => $relative_filename,
                    'directory_path' => $directory_path,
                    'output_directory_path' => $this->output_directory_path
                ]);

        return $filename;
    }

    /**
     * @throws ExceptionWithData
     */
    public function generate() : array {
        $generated_file_list = [];
        foreach ( $this->page_list as $page ) {

            $filename = $this->prepareOutputFilename($page->getRelativeFilename());
            $page->generate($filename);
            $generated_file_list[] = $page->getRelativeFilename();
        }
        return $generated_file_list;
    }
}