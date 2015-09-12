<?php
namespace wapmorgan\UnifiedDatabase\Databases;

class Dbase implements AbstractDatabase {
    const READ = 0;
    const WRITE = 1;
    const RW = 2;

    protected $dbase = false;

    public function __construct($filename, $mode = 0) {
        $this->dbase = dbase_open($filename, $mode);
    }

    public function __destruct() {
        dbase_close($this->dbase);
    }

    public function getNumberOfRows() {
        return dbase_numrecords($this->dbase);
    }

    public function getNumberOfFields() {
        return dbase_numfields($this->dbase);
    }

    public function getFields() {
        $fields = dbase_get_header_info($this->dbase);
        foreach ($fields as &$field)
            $field = (object)$field;
        return $fields;
    }

    /**
     * Retrieves record at position.
     * Note: position starts from 1 despite of agreement use 0 for first position.
     */
    public function getRow($position) {
        $result = dbase_get_record($this->dbase, $position);
        unset($result['deleted']);
        return array_map('trim', $result);
    }
}
