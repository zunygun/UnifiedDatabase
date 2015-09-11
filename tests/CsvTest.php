<?php
require __DIR__.'/../vendor/autoload.php';
use wapmorgan\UnifiedDatabase\UnifiedDatabase;

class CsvTest extends PHPUnit_Framework_TestCase {
    public function testAll() {
        $FILE = __DIR__.'/data/db.csv';

        $this->assertEquals(UnifiedDatabase::CSV, UnifiedDatabase::detectFormat($FILE));
        $csv = UnifiedDatabase::open($FILE, UnifiedDatabase::CSV);
        $this->assertInstanceOf('wapmorgan\UnifiedDatabase\Databases\Csv', $csv);
        $this->assertEquals(1, $csv->getNumberOfRows());
        $this->assertEquals(3, $csv->getNumberOfFields());
        $this->assertEquals(array((object)array('name' => 'name'), (object)array('name' => 'lastname'), (object)array('name' => 'age')), $csv->getFields());
        $this->assertEquals(array('John', 'Newman', '19'), $csv->getRow(0));
    }
}
