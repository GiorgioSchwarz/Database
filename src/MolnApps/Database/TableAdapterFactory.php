<?php

namespace MolnApps\Database;

use \MolnApps\Database\Adapter\MemoryTableAdapter;
use \MolnApps\Database\Adapter\BaseTableAdapter;

class TableAdapterFactory
{
	public function createTableAdapter(DsnDriver $dsn, $table)
	{
		$adapters = [
			'memory' => MemoryTableAdapter::class,
			'mysql' => BaseTableAdapter::class,
			'sqlite' => BaseTableAdapter::class,
		];
		
		if (isset($adapters[$dsn->getDriver()])) {
			return new $adapters[$dsn->getDriver()]($dsn, $table);
		}

		throw new \Exception('Please provide a valid driver.');
	}
}