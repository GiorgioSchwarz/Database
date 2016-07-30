<?php

namespace MolnApps\Database;

class TableGateway
{
	private static $driver;
	private static $registered = [];

	public function __construct(TableAdapter $tableAdapter)
	{
		$this->tableAdapter = ($tableAdapter);
	}

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

	public static function get($table)
	{
		if (isset(static::$registered[$table])) {
			return static::$registered[$table];
		}

		return static::create($table);
	}

	public static function create($table)
	{
		$adapter = TableAdapterFactory::instance()->createTableAdapter(Config::driver(), $table);

		return new static($adapter);
	}

	// ! Query methods

	public function select(array $query = [])
	{
		$query = $this->normalizeQuery($query);

		return $this->tableAdapter->select($query);
	}

	public function insert(array $assignments)
	{
		return $this->tableAdapter->insert($assignments);
	}

	public function lastInsertId()
	{
		return $this->tableAdapter->lastInsertId();
	}

	public function update(array $assignments, array $query)
	{
		$query = $this->normalizeQuery($query);

		return $this->tableAdapter->update($assignments, $query);
	}

	public function delete(array $query)
	{
		$query = $this->normalizeQuery($query);

		return $this->tableAdapter->delete($query);
	}

	// ! Utility methods

	private function normalizeQuery(array $query)
	{
		if ( 
			$query &&
			! isset($query['where']) && 
			! isset($query['columns']) && 
			! isset($query['limit'])
		) {
			$query = ['where' => $query];
		}

		return $query;
	}
}