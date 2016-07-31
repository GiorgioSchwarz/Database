<?php

namespace MolnApps\Database\Adapter;

use \MolnApps\Database\TableAdapter;

use \MolnApps\Database\Statement;

use \MolnApps\Database\Adapter\MemoryAdapterHelpers\Columns;
use \MolnApps\Database\Adapter\MemoryAdapterHelpers\Filter;
use \MolnApps\Database\Adapter\MemoryAdapterHelpers\Limit;
use \MolnApps\Database\Adapter\MemoryAdapterHelpers\Sort;

class MemoryTableAdapter implements TableAdapter
{
	private $index = 0;
	private $rows = [];

	public function select(array $query = [])
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
		$rows = $this->getMatchingRows($query, $this->rows);
		$rows = $this->getRequestedColumns($query, $rows);
		$rows = $this->getSortedRows($query, $rows);
		$rows = $this->getRowsWithinLimit($query, $rows);

		return $rows;
	}

	private function getMatchingRows(array $query, array $rows)
	{
		return Filter::create($rows, $query)->get();
	}

	private function getRequestedColumns(array $query, array $rows)
	{
		return Columns::create($rows, $query)->get();
	}

	private function getSortedRows(array $query, array $rows)
	{
		return Sort::create($rows, $query)->get();
	}

	private function getRowsWithinLimit(array $query, array $rows)
	{
		return Limit::create($rows, $query)->get();
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