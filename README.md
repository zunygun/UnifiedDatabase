# UnifiedDatabase
Unified interface to simple database files (dbase, paradox, filepro and csv).

[![Latest Stable Version](https://poser.pugx.org/wapmorgan/unified-database/v/stable)](https://packagist.org/packages/wapmorgan/unified-database) [![Latest Unstable Version](https://poser.pugx.org/wapmorgan/unified-database/v/unstable)](https://packagist.org/packages/wapmorgan/unified-database) [![License](https://poser.pugx.org/wapmorgan/unified-database/license)](https://packagist.org/packages/wapmorgan/unified-database)

Supported databases:
* DBase format (*.dbf files) - `UnifiedDatabase::DBASE`
* Paradox format (*.db files) - `UnifiedDatabase::PARADOX`
* Filepro format - `UnifiedDatabase::FILEPRO`
* CSV format (*.csv files) - `UnifiedDatabase::CSV`

## API

* **UnifiedDatabase**
    * *static* `open($filename, $format): AbstractDatabase`
    Creates a new object implementing `AbstractDatabase` interface.

    * *static* `detectFormat($filename): string`
    Detects format of a database by its filename or content.

* **AbstractDatabase**
    * `int getNumberOfRows()`
    Returns number of records in database
    * `int getNumberOfFields()`
    Returns number of fields in database
    * `array getFields()`
    Returns fields information. Each element is an object with following properties:
        * `name`
        * `type` (not present in csv)
        * `size` (not present in csv)
    * `array getRecord($position)`
    Returns array with record fields data

## Example of usage
``` php
$db = UnifiedDatabase::open($file, UnifiedDatabase::detectFormat($file));
$total = $db->getNumberOfRows();
for ($i = 0; $i < $total; $i++) {
    $row = $db->getRecord($i);
    echo implode(', ', $row).PHP_EOL;
}
```
