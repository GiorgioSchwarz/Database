<?php

namespace MolnApps\Database\Testing;

use \PDO;
use \PHPUnit_Extensions_Database_TestCase as PHPUnit_DbTestCase;

abstract class DbTestCase extends PHPUnit_DbTestCase
{
    private $pdo;

    protected function setUp()
    {
        parent::setUp();

        if ($this->isUsingSqlite()) {
            $this->resetSqliteAutoincrement();
        }
    }

    private function isUsingSqlite()
    {
        return $this->getDsn()['driver'] == 'sqlite';
    }

    private function resetSqliteAutoincrement()
    {
        foreach ($this->getAllTables() as $table) {
            $stmt = $this->pdo->prepare("DELETE FROM SQLITE_SEQUENCE WHERE name = ?");
            $stmt->execute([$table]);
        }
    }

    private function getAllTables()
    {
        return array_keys($this->getDataSetArray());
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
    	$dsn = $this->getDsn();
    	
    	$this->pdo = new PDO($dsn['dsn'], $dsn['username'], $dsn['password']);
        
        return $this->createDefaultDBConnection($this->pdo, $dsn['database']);
    }

    abstract protected function getDsn();

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return new ArrayDataSet($this->getDataSetArray());
    }

    abstract protected function getDatasetArray();
}