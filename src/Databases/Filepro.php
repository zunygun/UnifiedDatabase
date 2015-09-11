<?php
namespace wapmorgan\UnifiedDatabase\Databases;

class Filepro implements AbstractDatabase {
    public function __construct($filename) {
        filepro($filename);
    }

    public function __destruct() {
    }

    public function getNumberOfRows() {
        return filepro_fieldcount();
    }

    public function getNumberOfFields() {
        return filepro_rowcount();
    }

    public function getFields() {
        $count = filepro_fieldcount();
        $fields = array();
        for ($i = 0; $i < $fields; $i++)
            $fields[] = (object)array(
                'name' => filepro_fieldname($i),
                'type' => filepro_fieldtype($i),
                'size' => filepro_fieldwidth($i)
            );
        return $fields;
    }

    public function getRow($position) {
        $fields = filepro_fieldcount();
        $record = array();
        for ($i = 0; $i < $fields; $i++)
            $record[$i] = filepro_retrieve($position, $i);
        return $record;
    }
}
