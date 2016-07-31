<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Dsn;
use \MolnApps\Database\TableAdapterFactory;

use \MolnApps\Database\Adapter\TableAdapterTests;

class MemoryAdapterTest extends \PHPUnit_Framework_TestCase
{
	use TableAdapterTests;

	private $tableAdapters = [];

	protected function setUp()
	{
		$this->createTableAdapter('people');
		
		foreach ($this->getDatasetArray()['people'] as $row) {
			$this->createTableAdapter('people')->insert($row);
		}
	}

	protected function tearDown()
	{
		$this->tableAdapters = [];
	}

	protected function createTableAdapter($table)
	{
		if ( ! isset($this->tableAdapters[$table])) {
			$this->tableAdapters[$table] = TableAdapterFactory::instance()
				->createTableAdapter($this->createDsn(), $table);
		}
		
		return $this->tableAdapters[$table];
	}

	private function createDsn()
	{
		return new Dsn('memory', []);
	}
}