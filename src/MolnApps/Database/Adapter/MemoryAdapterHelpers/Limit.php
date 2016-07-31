<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Limit
{
	private $rows;

	public function __construct(array $rows, array $query)
	{
		$this->rows = $rows;
		$this->result = $this->limit($query);
	}

	public static function create(array $rows, array $query)
	{
		return new static($rows, $query);
	}

	public function get()
	{
		return $this->result;
	}

	private function limit($query)
	{
		$result = [];

		foreach ($this->rows as $i => $row) {
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
}