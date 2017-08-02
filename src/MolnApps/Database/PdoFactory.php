<?php

namespace MolnApps\Database;

class PdoFactory
{
	public static function createPdo($driver, array $dsn = [])
	{
		try {
			if ($driver == 'sqlite') {
				return new \Pdo('sqlite:' . $dsn['database']);
			}

			if ($driver == 'mysql') {
				return new \Pdo(
					'mysql:host='.$dsn['host'].';dbname='.$dsn['database'], 
					$dsn['username'], 
					$dsn['password']
				);
			}
		} catch (Exception $e) {
			die('Could not connect to the database');
		}
	}
}