<?php
namespace wapmorgan\UnifiedDatabase\Databases;

class Paradox implements AbstractDatabase {
    protected $paradox = false;
    protected $px_file = false;

    public function __construct($filename) {
        $this->paradox = px_new();
        $this->px_file = fopen($filename, 'rb');
        px_open_fp($this->paradox, $this->px_file);
    }

    public function __destruct() {
        px_close($this->paradox);
        fclose($this->px_file);
    }

    public function getNumberOfRows() {
        return px_numrecords($this->paradox);
    }

    public function getNumberOfFields() {
        return px_numfields($this->paradox);
    }

    public function getFields() {
        $schema = px_get_schema($this->paradox);
                $fields = array();
                foreach ($schema as $field => $field_d) {
                    $fields[] = (object)array('name' => $field, 'size' => $field_d['size'], 'type' => $field_d['type']);
                }
                return $fields;
    }

    public function getRow($position) {
        return px_get_record($this->paradox, $position);
    }
}
