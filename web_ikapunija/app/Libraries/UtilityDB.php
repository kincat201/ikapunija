<?php

namespace App\Libraries;

class UtilityDB {
    public function excludeTable($query, $arrTable, $columns = [])
    {
        foreach ($columns as $value) {
            foreach (array_keys($arrTable, $value) as $key) {
                unset($arrTable[$key]);
            }
        }

        return $query->select($arrTable);
    }
}