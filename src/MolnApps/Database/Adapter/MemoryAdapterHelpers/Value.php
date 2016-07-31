<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Value
{
	private $row = [];

	public function __construct(array $row)
	{
		$this->row = $row;
	}

	public static function create(array $row)
	{
		return new static($row);
	}

	public function matches($where)
	{
		$where = Where::create($where)->toArray();
		
		foreach ($where as $clause) {
			if ( ! $this->rowMatchesClause($clause)) {
				return false;
			}
		}

		return true;
	}

	private function rowMatchesClause($clause)
	{
		list ($column, $operator, $value) = $clause;
				
		if ( 
			! isset($this->row[$column]) || 
			! $this->valueMatches($this->row[$column], $operator, $value)
		) {
			return false;
		}
		
		return true;
	}

	private function valueMatches($columnValue, $operator, $expectedValue)
	{
		if ($operator == 'eq' && $columnValue == $expectedValue) {
			return true;
		}

		if ($operator == 'not' && $columnValue != $expectedValue) {
			return true;
		}
		
		if ($operator == 'gt' && $columnValue > $expectedValue) {
			return true;
		}
		
		if ($operator == 'lt' && $columnValue < $expectedValue) {
			return true;
		}
		
		if ($operator == 'gte' && $columnValue >= $expectedValue) {
			return true;
		}
		
		if ($operator == 'lte' && $columnValue <= $expectedValue) {
			return true;
		}
		
		if ($operator == 'contains' && stripos($columnValue, $expectedValue) !== false) {
			return true;
		}

		if (in_array($operator, ['in', 'notIn']) && ! $expectedValue) {
			throw new \Exception('No expected values for IN clause');
		}

		if ($operator == 'in' && in_array($columnValue, $expectedValue)) {
			return true;
		}

		if ($operator == 'notIn' && ! in_array($columnValue, $expectedValue)) {
			return true;
		}
		
		return false;
	}
}