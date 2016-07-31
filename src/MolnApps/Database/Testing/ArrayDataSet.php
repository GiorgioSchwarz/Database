<?php

namespace MolnApps\Database\Testing;

use \PHPUnit_Extensions_Database_DataSet_AbstractDataSet;
use \PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData;
use \PHPUnit_Extensions_Database_DataSet_DefaultTable;
use \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator;

use \InvalidArgumentException;

class ArrayDataSet extends \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
{
    protected $tables = [];

    public function __construct(array $data)
    {
        foreach ($data AS $tableName => $rows) {
            $columns = $this->getColumns($rows);

            $metaData = new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($tableName, $columns);
            $table = new PHPUnit_Extensions_Database_DataSet_DefaultTable($metaData);

            foreach ($rows AS $row) {
                $table->addRow($row);
            }

            $this->tables[$tableName] = $table;
        }
    }

    private function getColumns($rows)
    {
        $columns = [];

        if (isset($rows[0])) {
            $columns = array_keys($rows[0]);
        }

        return $columns;
    }

    protected function createIterator($reverse = false)
    {
        return new PHPUnit_Extensions_Database_DataSet_DefaultTableIterator($this->tables, $reverse);
    }

    public function getTable($tableName)
    {
        if ( ! isset($this->tables[$tableName])) {
            throw new InvalidArgumentException("$tableName is not a table in the current database.");
        }

        return $this->tables[$tableName];
    }
}