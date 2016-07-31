<?php

namespace MolnApps\Database\Adapter;

use \Pdo;
use \MolnApps\Database\Dsn;

class MysqlTableAdapter extends AbstractTableAdapter
{
	protected function createPdo(Dsn $dsn)
	{
		$dsn = $dsn->getDsn();
		
		return new Pdo(
			'mysql:host='.$dsn['host'].';dbname='.$dsn['database'], 
			$dsn['username'], 
			$dsn['password']
		);
	}
}