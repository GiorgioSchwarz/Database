<?php

namespace MolnApps\Database\Adapter;

use \Pdo;
use \MolnApps\Database\Config;

class SqliteTableAdapter extends AbstractTableAdapter
{
	protected function createPdo()
	{
		$dsn = Config::dsn();

		return new Pdo('sqlite:' . $dsn['database']);
	}
}