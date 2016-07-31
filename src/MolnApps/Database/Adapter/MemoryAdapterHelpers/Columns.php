<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Columns
{
	public function __construct(array $rows, array $query)
	{
		$this->result = $this->columns($rows, $query);
	}

	public static function create(array $rows, array $query)
	{
		return new static($rows, $query);
	}

	public function get()
	{
		return $this->result;
	}

	private function columns(array $rows, array $query)
	{
		$columns = [];
		
		foreach ($rows as $row) {
			$columns[] = $this->getOnlyRequestedColumns($row, $query);
		}
		
		return $columns;
	}

	private function getOnlyRequestedColumns(array $row, $query)
	{
		$columns = isset($query['columns']) ? $query['columns'] : array_keys($row);
		
		$result = [];
		
		foreach ($columns as $column) {
			$result[$column] = $row[$column];
		}
		
		return $result;
	}
}