<?php

namespace MolnApps\Database;

class TableGateway
{
	private $tableAdapter;
	
	public function __construct(TableAdapter $tableAdapter)
	{
		$this->tableAdapter = $tableAdapter;
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
			! isset($query['limit']) &&
			! isset($query['offset']) &&
			! isset($query['order'])
		) {
			$query = ['where' => $query];
		}

		return $query;
	}
}