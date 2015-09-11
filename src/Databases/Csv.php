<?php
namespace wapmorgan\UnifiedDatabase\Databases;

function countLinesInFile($filename) {
    $fp = fopen($filename, 'r');
    $lines = 0;
    while (fgets($fp) !== false)
        $lines++;
    fclose($fp);
    return $lines;
}

class Csv implements AbstractDatabase {
    protected $csv = false;
    protected $csv_header = false;
    protected $csv_records = false;

    public function __construct($filename) {
        $this->csv = fopen($filename, 'r');
        $this->csv_header = fgetcsv($this->csv);
        $this->csv_records = countLinesInFile($filename) - 1;
    }

    public function __destruct() {
        fclose($this->csv);
    }

    public function getNumberOfRows() {
        return $this->csv_records;
    }

    public function getNumberOfFields() {
        return count($this->csv_header);
    }

    public function getFields() {
        $fields = array();
        foreach ($this->csv_header as $field)
            $fields[] = (object)array('name' => $field);
        return $fields;
    }

    public function getRow($position) {
        fseek($this->csv, 0);
        fgets($this->csv);
        $i = 0;
        while ($i < $position)
            fgets($this->csv);
        return fgetcsv($this->csv);
    }
}
