<?php
declare(strict_types=1);

namespace edwrodrig\staty;

use edwrodrig\exception_with_data\ExceptionWithData;

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
    public function set_page_list(array $page_list) : void {
        $this->page_list = $page_list;
    }

    /**
     * @param string $relative_filename
     * @return string
     * @throws ExceptionWithData
     */
    public function prepare_output_filename(string $relative_filename) : string {
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
    public function generate() {
        foreach ( $this->page_list as $page ) {
            $filename = $this->prepare_output_filename($page->get_relative_filename());
            file_put_contents($filename, $page->get_content());
        }
    }
}