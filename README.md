# UnifiedDatabase
Unified interface to simple database files (dbase, paradox, filepro and csv).

Supported databases:
* DBase format (*.dbf files) - `UnifiedDatabase::DBASE`
* Paradox format (*.db files) - `UnifiedDatabase::PARADOX`
* Filepro format - `UnifiedDatabase::FILEPRO`
* CSV format (*.csv files) - `UnifiedDatabase::CSV`

## API
```php
__construct($filename, $format)
```

Creates a new UnifiedDatabase object

```php
int getNumberOfRows()
```

Returns number of records in database

```php
int getNumberOfFields()
```

Returns number of fields in database

```php
array getFields()
```

Returns fields information. Each element is an object with following properties:

* `name`
* `type` (not present in csv)
* `size` (not present in csv)

```php
array getRecord($position)
```

Returns array with record fields data

### Static methods
```php
format detectFormat($filename)
```
Tries to detect format by file content. Returns format as one of constants or false in case of failure.
