<?php

namespace MolnApps\Database;

class Config
{
	private static $driver;
	
	private static $dsn = [
		'host' => '',
		'database' => '',
		'username' => '',
		'password' => '',
	];

	public static function reset()
	{
		static::$driver = null;
		static::$dsn = [
			'host' => '',
			'database' => '',
			'username' => '',
			'password' => '',
		];
	}

	public static function setDriver($driver)
	{
		static::$driver = $driver;
	}

	public static function setDsn(array $dsn)
	{
		static::$dsn = $dsn;
	}

	public static function driver()
	{
		return static::$driver;
	}

	public static function dsn()
	{
		return static::$dsn;
	}
}