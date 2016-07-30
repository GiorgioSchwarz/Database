<?php

namespace MolnApps\Database;

class TableAdapterFactory
{
	public static function instance()
	{
		return new static;
	}
	
	public function createTableAdapter($driver, $table)
	{
		switch ($driver)
		{
			case 'memory':
				return new MemoryTableAdapter;
			case 'sqlite':
				return new SqliteTableAdapter($table);
			case 'mysql':
				return new MysqlTableAdapter($table);
		}
	}
}