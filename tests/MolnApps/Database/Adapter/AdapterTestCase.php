<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Testing\DbTestCase;

use \MolnApps\Database\Dsn;
use \MolnApps\Database\TableAdapterFactory;

use \MolnApps\Database\Adapter\TableAdapterTests;

abstract class AdapterTestCase extends DbTestCase
{
	use TableAdapterTests;

	protected function createTableAdapter($table)
	{
		return TableAdapterFactory::instance()->createTableAdapter($this->createDsn(), $table);
	}

	private function createDsn()
	{
		return new Dsn($this->getDsn()['driver'], $this->getDsn());
	}
}