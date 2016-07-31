<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Filter
{
	public function __construct(array $rows, array $query)
	{
		$this->result = $this->filter($rows, $query);
	}

	public static function create(array $rows, array $query)
	{
		return new static($rows, $query);
	}

	public function get()
	{
		return $this->result;
	}

	private function filter(array $rows, array $query)
	{
		$result = [];

		foreach ($rows as $row) {
			if ($this->rowMatches($query, $row)) {
				$result[] = $row;
			}
		}

		return $result;
	}

	private function rowMatches($query, $row)
	{
		$where = isset($query['where']) ? $query['where'] : null;
		return ( ! $where || Value::create($row)->matches($where));
	}
}