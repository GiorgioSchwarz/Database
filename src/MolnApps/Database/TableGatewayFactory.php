<?php

namespace MolnApps\Database;

class TableGatewayFactory
{
	private static $driver;
	private static $registered = [];

	// ! Factory and testing methods

	public static function register($table, TableGateway $tableGateway = null)
	{
		$tableGateway = ($tableGateway) ?: static::create($table);
		
		static::$registered[$table] = $tableGateway;

		return $tableGateway;
	}

	public static function reset()
	{
		static::$registered = [];
	}

	public static function get($table, Dsn $dsn = null)
	{
		if (isset(static::$registered[$table])) {
			return static::$registered[$table];
		}

		return static::create($table, $dsn);
	}

	public static function create($table, Dsn $dsn = null)
	{
		if ( ! $dsn) {
			$dsn = new Dsn(Config::driver(), Config::dsn());
		}

		$adapter = TableAdapterFactory::instance()->createTableAdapter($dsn, $table);

		return new TableGateway($adapter);
	}
}