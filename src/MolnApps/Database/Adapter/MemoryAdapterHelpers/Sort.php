<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Sort
{
	public function __construct(array $rows, array $query)
	{
		$this->result = $this->sort($rows, $query);
	}

	public static function create(array $rows, array $query)
	{
		return new static($rows, $query);
	}

	public function get()
	{
		return $this->result;
	}

	private function sort(array $rows, $query)
	{
		if ( ! isset($query['order'])) {
			return $rows;
		}

		$order = $this->normalizeOrder($query['order']);

		$args = [];

		foreach ($order as $orderKey => $direction) {
			$args[] = $this->getIndexForSortKey($rows, $orderKey);
			$args[] = $this->getSortConstant($direction);
		}

		$args[] = &$rows;

		call_user_func_array('array_multisort', $args);

		return array_pop($args);
	}

	private function getIndexForSortKey(array $rows, $orderKey)
	{
		$tmp = [];
		
		foreach ($rows as $key => $row) {
			$tmp[$key]  = $row[$orderKey];
		}

		return $tmp;
	}

	private function getSortConstant($direction)
	{
		return ($direction == 'asc') ? SORT_ASC : SORT_DESC;
	}

	private function normalizeOrder(array $order)
	{
		$result = [];
		
		foreach ($order as $key => $direction) {
			if (is_numeric($key)) {
				$result[$direction] = 'asc';
			} else {
				$result[$key] = $direction;
			}
		}

		return $result;
	}
}