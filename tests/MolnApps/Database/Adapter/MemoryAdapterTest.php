<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Container\Container;

use \MolnApps\Database\Dsn;
use \MolnApps\Database\TableAdapterFactory;

use \MolnApps\Database\Adapter\TableAdapterTests;

class MemoryAdapterTest extends \PHPUnit_Framework_TestCase
{
	use TableAdapterTests;

	private $tableAdapters = [];

	/** @before */
	protected function setUpTableAdapterFactory()
	{
		$this->tableAdapterFactory = Container::get('tableAdapterFactory');
	}

	/** @before */
	protected function setUpWorld()
	{
		$this->createTableAdapter('people');
		
		foreach ($this->getDatasetArray()['people'] as $row) {
			$this->createTableAdapter('people')->insert($row);
		}
	}

	/** @after */
	protected function tearDownTableAdapters()
	{
		$this->tableAdapters = [];
	}
	
	/** @after */
	protected function tearDownContainer()
	{
		Container::reset();
	}

	protected function createTableAdapter($table)
	{
		if ( ! isset($this->tableAdapters[$table])) {
			$this->tableAdapters[$table] = $this->tableAdapterFactory->createTableAdapter($this->createDsn(), $table);
		}
		
		return $this->tableAdapters[$table];
	}

	private function createDsn()
	{
		return new Dsn('memory', null);
	}
}