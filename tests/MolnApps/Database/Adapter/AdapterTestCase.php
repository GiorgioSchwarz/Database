<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Testing\DbTestCase;

use \MolnApps\Database\Container\Container;

use \MolnApps\Database\Dsn;
use \MolnApps\Database\TableAdapterFactory;

use \MolnApps\Database\Adapter\TableAdapterTests;

abstract class AdapterTestCase extends DbTestCase
{
	use TableAdapterTests;

	/** @before */
	protected function setUpTableAdapterFactory()
	{
		$this->tableAdapterFactory = Container::get('tableAdapterFactory');
	}

	/** @after */
	protected function tearDownContainer()
	{
		Container::reset();
	}

	protected function createTableAdapter($table)
	{
		return $this->tableAdapterFactory->createTableAdapter($this->createDsn(), $table);
	}

	private function createDsn()
	{
		return new Dsn($this->getDsn()['driver'], $this->pdo);
	}
}