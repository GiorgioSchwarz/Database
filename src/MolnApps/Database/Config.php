<?php

namespace MolnApps\Database;

class Config
{
	public static function driver()
	{
		return getenv('DB_DRIVER');
	}

	public static function dsn()
	{
		return [
			'host' => getenv('DB_HOST'),
			'database' => getenv('DB_NAME'),
			'username' => getenv('DB_USERNAME'),
			'password' => getenv('DB_PASSWORD'),
		];
	}
}