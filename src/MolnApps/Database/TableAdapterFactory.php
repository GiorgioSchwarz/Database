<?php

namespace MolnApps\Database;

use \MolnApps\Database\Adapter\MemoryTableAdapter;
use \MolnApps\Database\Adapter\MysqlTableAdapter;
use \MolnApps\Database\Adapter\SqliteTableAdapter;

class TableAdapterFactory
{
	public static function instance()
	{
		return new static;
	}
	
	public function createTableAdapter(Dsn $dsn, $table)
	{
		$adapters = [
			'memory' => MemoryTableAdapter::class,
			'mysql' => MysqlTableAdapter::class,
			'sqlite' => SqliteTableAdapter::class,
		];
		
		if (isset($adapters[$dsn->getDriver()])) {
			return new $adapters[$dsn->getDriver()]($table);
		}

		throw new \Exception('Please provide a valid driver.');
	}
}