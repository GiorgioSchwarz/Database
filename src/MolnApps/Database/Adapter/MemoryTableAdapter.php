<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\Value\Value;
use \MolnApps\Database\TableAdapter;
use \MolnApps\Database\Statement;

class MemoryTableAdapter implements TableAdapter
{
	private $index = 0;
	private $rows = [];

	public function select(array $query)
	{
		if ( ! $query) {
			return $this->returnAllRows();
		}

		return $this->returnRequestedRows($query);
	}

	private function returnAllRows()
	{
		return array_values($this->rows);
	}

	private function returnRequestedRows(array $query)
	{
		$rows = [];

		foreach ($this->rows as $row) {
			if ($this->rowMatches($query, $row)) {
				$rows[] = $this->getOnlyRequestedColumns($query, $row);
			}
		}

		return $this->trimResultsToLimits($query, $rows);
	}

	private function rowMatches($query, $row)
	{
		$where = isset($query['where']) ? $query['where'] : null;
		return ( ! $where || Value::create($row)->matches($where));
	}

	private function getOnlyRequestedColumns($query, $row)
	{
		$columns = isset($query['columns']) ? $query['columns'] : array_keys($row);
		
		$result = [];
		
		foreach ($columns as $column) {
			$result[$column] = $row[$column];
		}
		
		return $result;
	}

	private function trimResultsToLimits($query, $rows)
	{
		$result = [];

		foreach ($rows as $i => $row) {
			if (
				$this->currentRowIsAboveOffset($query, $i) && 
				$this->selectedRowsAreUnderLimit($query, $result)
			) {
				$result[] = $row;
			}
		}

		return $result;
	}

	private function currentRowIsAboveOffset($query, $i)
	{
		$offset = isset($query['offset']) ? $query['offset'] : 0;
		return $i >= $offset;
	}

	private function selectedRowsAreUnderLimit($query, $rows)
	{
		$limit = isset($query['limit']) ? $query['limit'] : count($this->rows);
		return count($rows) < $limit;
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