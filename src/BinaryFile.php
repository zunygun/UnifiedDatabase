<?php
namespace wapmorgan\UnifiedDatabase;

class BinaryFile {
    static public function checkSignature($file, $offset = 0, array $bytes) {
        $fp = fopen($file, 'rb');
        fseek($fp, $offset, SEEK_SET);
        foreach ($bytes as $byte) {
            $char = fgetc($fp);
            if ($char !== $byte)
                return false;
        }
        return true;
    }
}
