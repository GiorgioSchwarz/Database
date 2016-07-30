<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Value\Value;
use \MolnApps\Database\TableAdapter;

class MemoryTableAdapter implements TableAdapter
{
	private $index = 0;
	private $rows = [];

	public function select(array $query)
	{
		if ( ! $query) {
			return array_values($this->rows);
		}

		$where = isset($query['where']) ? $query['where'] : null;
		$limit = isset($query['limit']) ? $query['limit'] : count($this->rows);

		$rows = [];

		foreach ($this->rows as $row) {
			if ( 
				count($rows) < $limit &&
				( ! $where || Value::create($row)->matches($where))
			) {
				$rows[] = $row;
			}
		}

		return $rows;
	}

	public function insert(array $assignments)
	{
		$this->index++;

		$this->rows[$this->index] = array_merge($assignments, ['id' => $this->index]);
	}

	public function update(array $assignments, array $query)
	{
		$rows = $this->select($query);

		foreach ($rows as $row) {
			$this->rows[$row['id']] = array_merge($row, $assignments);
		}
	}

	public function delete(array $query)
	{
		$rows = $this->select($query);

		foreach ($rows as $row) {
			unset($this->rows[$row['id']]);
		}
	}

	public function lastInsertId()
	{
		return $this->index;
	}

	public function executeSelect(Statement $statement)
	{
		throw new \Exception('Needs implementation');
	}

	public function executeUpdate(Statement $statement)
	{
		throw new \Exception('Needs implementation');
	}
}