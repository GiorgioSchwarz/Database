<?php

namespace MolnApps\Database\Adapter;

use \Pdo;
use \MolnApps\Database\Config;

class MysqlTableAdapter extends AbstractTableAdapter
{
	protected function createPdo()
	{
		$dsn = Config::dsn();
		
		return new Pdo(
			'mysql:host='.$dsn['host'].';dbname='.$dsn['database'], 
			$dsn['username'], 
			$dsn['password']
		);
	}
}