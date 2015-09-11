<?php
namespace wapmorgan\UnifiedDatabase\Databases;

interface AbstractDatabase {
    public function __construct($filename);
    public function __destruct();
    public function getNumberOfRows();
    public function getNumberOfFields();
    public function getFields();
    public function getRow($position);
}
