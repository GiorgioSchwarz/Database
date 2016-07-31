<?php

namespace MolnApps\Database\Adapter;

class MysqlAdapterTest extends AdapterTestCase
{
	protected function getDsn()
	{
		return [
			'dsn' => 'mysql:host='.$GLOBALS['DB_MYSQL_HOST'].';dbname='.$GLOBALS['DB_MYSQL_DATABASE'],
			'driver' => 'mysql',
			'host' => $GLOBALS['DB_MYSQL_HOST'],
			'database' => $GLOBALS['DB_MYSQL_DATABASE'],
			'username' => $GLOBALS['DB_MYSQL_USERNAME'],
			'password' => $GLOBALS['DB_MYSQL_PASSWORD']
		];
	}
}