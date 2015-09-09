<?php
function countLinesInFile($filename) {
    $fp = fopen($filename, 'r');
    $lines = 0;
    while (fgets($fp) !== false)
        $lines++;
    fclose($fp);
    return $lines;
}
