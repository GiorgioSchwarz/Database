<?php

namespace MolnApps\Database\Adapter;

class SqliteAdapterTest extends AdapterTestCase
{
	protected function getDsn()
	{
		return [
			'dsn' => 'sqlite:'.$GLOBALS['DB_SQLITE_DATABASE'],
			'driver' => 'sqlite',
			'database' => $GLOBALS['DB_SQLITE_DATABASE'],
			'username' => '',
			'password' => '',
		];
	}
}