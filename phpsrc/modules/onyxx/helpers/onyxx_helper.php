<?php

function infer_type($type) {
    switch ($type) {
        case 'char':
        case 'varchar':
            return 'string';
        case 'tinyint':
            return 'bool';
        case 'timestamp':
            return 'datetime';
        case 'binary':
        case 'blob':
            return 'file';
        default: return $type;
    }
}
