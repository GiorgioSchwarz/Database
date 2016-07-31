<?php

namespace MolnApps\Database\Adapter;

use \Pdo;
use \MolnApps\Database\Dsn;

class SqliteTableAdapter extends AbstractTableAdapter
{
	protected function createPdo(Dsn $dsn)
	{
		$dsn = $dsn->getDsn();

		return new Pdo('sqlite:' . $dsn['database']);
	}
}