<?php
namespace wapmorgan\UnifiedDatabase;

use \Exception;

class UnifiedDatabase {
    const DBASE = 'dbase';
    const FILEPRO = 'filepro';
    const PARADOX = 'paradox';
    const CSV = 'csv';

    public static function open($filename, $format) {
        switch ($format) {
            case self::DBASE:
                return new Databases\Dbase($filename);
            case self::PARADOX:
                return new Databases\Paradox($filename);
            case self::FILEPRO:
                return new Databases\Filepro($filename);
            case self::CSV:
                return new Databases\Csv($filename);
            default:
                throw new Exception('Unknown database format: '.$format);
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
