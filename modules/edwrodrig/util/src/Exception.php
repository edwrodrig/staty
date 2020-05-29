<?php
declare(strict_types=1);

namespace edwrodrig\util;

class Exception extends \Exception
{
    protected array $structured_data;

    public static function create(array $structured_data) : Exception {
        return new Exception($structured_data);
    }

    public function __construct(array $structured_data) {
        parent::__construct($structured_data['message'] ?? 'Some error occurred');
        $this->structured_data = $structured_data;
    }

    public function get_structured_data() : array {
        return $this->structured_data;
    }
}