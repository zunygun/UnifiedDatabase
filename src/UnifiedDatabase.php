<?php
namespace wapmorgan\UnifiedDatabase;

class UnifiedDatabase {
    const DBASE = 'dbase';
    const FILEPRO = 'filepro';
    const PARADOX = 'paradox';
    const CSV = 'csv';

    protected $type;

	protected $dbase = false;
    protected $filepro = false;
    protected $paradox = false;
    protected $px_file = false;
    protected $csv = false;
    protected $csv_header = false;

    public function __construct($filename, $format, $options = array()) {
        switch ($format) {
            case self::DBASE:
                $this->dbase = dbase_open($filename, 0);
                break;

            case self::PARADOX:
                $this->paradox = px_new();
                $this->px_file = fopen($filename, 'rb');
                px_open_fp($this->px_obj, $this->px_file);
                break;

            case self::FILEPRO:
                $this->filepro = true;
                filepro($filename);
                break;

            case self::CSV:
                $this->csv = fopen($filename, 'r');
                $this->csv_header = fgetcsv($this->csv);
                $this->csv_records = countLinesInFile($filename) - 1;
                break;

            default:
                throw new Exception('Unkown database type: '.$format);
        }
        $this->type = $format;
    }

    public function __destruct() {
        switch ($this->type) {
            case self::DBASE:
                dbase_close($this->dbase);
                break;

            case self::PARADOX:
                px_close($this->paradox);
                fclose($this->px_file);
                break;

            case self::FILEPRO:
                break;

            case self::CSV:
                fclose($this->csv);
                break;
        }
    }

    public function getNumberOfRows() {
        switch ($this->type) {
            case self::DBASE:
                return dbase_numrecords($this->dbase);

            case self::PARADOX:
                return px_numrecords($this->paradox);

            case self::FILEPRO:
                return filepro_rowcount();

            case self::CSV:
                return $this->csv_records;
        }
    }

    public function getNumberOfFields() {
        switch ($this->type) {
            case self::DBASE:
                return dbase_numfields($this->dbase);

            case self::PARADOX:
                return px_numfields($this->paradox);

            case self::FILEPRO:
                return filepro_fieldcount();

            case self::CSV:
                return count($this->csv_header);
        }
    }

    public function getFields() {
        switch ($this->type) {
            case self::DBASE:
                $fields = dbase_get_header_info($this->dbase);
                foreach ($fields as &$field)
                    $field = (object)$field;
                return $fields;

            case self::PARADOX:
                $schema = px_get_schema($this->paradox);
                $fields = array();
                foreach ($schema as $field => $field_d) {
                    $fields[] = (object)array('name' => $field, 'size' => $field_d['size'], 'type' => $field_d['type']);
                }
                return $fields;

            case self::FILEPRO:
                $count = filepro_fieldcount();
                $fields = array();
                for ($i = 0; $i < $fields; $i++)
                    $fields[] = (object)array(
                        'name' => filepro_fieldname($i),
                        'type' => filepro_fieldtype($i),
                        'size' => filepro_fieldwidth($i)
                    );
                return $fields;

            case self::CSV:
                $fields = array();
                foreach ($this->csv_header as $field)
                    $fields[] = (object)array('name' => $field);
                return $fields;
        }
    }

    public function getRecord($position) {
        switch ($this->type) {
            case self::DBASE:
                return dbase_get_record($this->dbase, $position);

            case self::PARADOX:
                return px_get_record($this->paradox, $position);

            case self::FILEPRO:
                $fields = filepro_fieldcount();
                $record = array();
                for ($i = 0; $i < $fields; $i++)
                    $record[$i] = filepro_retrieve($position, $i);
                return $record;

            case self::CSV:
                fseek($this->csv, 0);
                fgets($this->csv);
                $i = 0;
                while ($i < $position)
                    fgets($this->csv);
                return fgetcsv($this->csv);

        }
    }

    public static function detectFormat($filename) {
        if (BinaryFile::checkSignature($filename, 0, array(0x4F, 0x50, 0x4C, 0x44, 0x61, 0x74, 0x61, 0x62))) {
            return self::DBASE;
        } else if (is_dir($filename)) {
            return self::PARADOX;
        } else if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) == 'csv') {
            return self::CSV;
        } else if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) == 'db') {
            return self::FILEPRO;
        } else {
            return false;
        }
    }
}
