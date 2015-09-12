<?php
require __DIR__.'/../vendor/autoload.php';
use wapmorgan\UnifiedDatabase\UnifiedDatabase;

class DbaseTest extends PHPUnit_Framework_TestCase {
    public function testAll() {
        if (!extension_loaded('dbase')) {
            $this->markTestSkipped('dbase extensions is not loaded');
        }
        $FILE = __DIR__.'/data/DB.dbf';

        $this->assertEquals(UnifiedDatabase::DBASE, UnifiedDatabase::detectFormat($FILE));
        $db = UnifiedDatabase::open($FILE, UnifiedDatabase::DBASE);
        $this->assertInstanceOf('wapmorgan\UnifiedDatabase\Databases\Dbase', $db);
        $this->assertEquals(1, $db->getNumberOfRows());
        $this->assertEquals(3, $db->getNumberOfFields());
        $fields = $db->getFields();
        $this->assertCount(3, $fields);
        $this->assertEquals('NAME', $fields[0]->name);
        $this->assertEquals('LASTNAME', $fields[1]->name);
        $this->assertEquals('AGE', $fields[2]->name);
        $this->assertEquals(array('John', 'Newman', 18), $db->getRow(1));
    }
}
