<?php

namespace MolnApps\Database;

use \MolnApps\Database\Container\Container;

class TableGatewayFactory
{
	private $registered = [];

	public function registerTable($table, TableGateway $tableGateway = null)
	{
		$tableGateway = $tableGateway ?: $this->createTable($table);
		
		$this->registered[$table] = $tableGateway;

		return $tableGateway;
	}

	public function getTable($table, DsnDriver $dsn = null)
	{
		if (isset($this->registered[$table])) {
			return $this->registered[$table];
		}

		return $this->createTable($table, $dsn);
	}

	public function createTable($table, DsnDriver $dsn = null)
	{
		$dsn = $dsn ?: Container::get('dsn');
		
		$adapter = Container::get('tableAdapterFactory')->createTableAdapter($dsn, $table);

		return new TableGateway($adapter);
	}
}