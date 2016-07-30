<?php

namespace MolnApps\Database\Statement\Sql;

class WhereClause
{
	private $stringOperatorsMap = [
		'eq'  => '=',
		'not' => '!=',
		'gt'  => '>',
		'gte' => '>=',
		'lt'  => '<',
		'lte' => '<=',
	];

	private $clause;
	private $parts = [];
	private $params = [];

	public function __construct(array $values, $combinedBy = 'AND')
	{
		foreach ($values as $key => $value) {
			$value = $this->getNormalizedPart($key, $value);
			list ($clause, $clauseParams) = $this->convertArrayToClausePart($value);
			$this->appendPart($clause);
			$this->appendParams($clauseParams);
		}
		
		$this->clause = implode(' '.trim($combinedBy).' ', $this->parts);
	}

	public function getClause()
	{
		return $this->clause;
	}

	public function getParams()
	{
		return $this->params;
	}

	private function getNormalizedPart($column, $value)
	{
		if (is_array($value)) {
			return $value;
		} else {
			return [$column, '=', $value];
		}
	}

	private function convertArrayToClausePart(array $value)
	{
		list ($column, $operator, $v) = $value;

		$operator = $this->convertStringOperator($operator);
		
		$operatorWhitelist = array('=', '!=', '<', '>', '<=', '>=');
		if (in_array($operator, $operatorWhitelist)) {
			return $this->getClauseWithOperator($column, $operator, $v);
		}

		$functionWhitelist = array('in', 'not in', 'between', 'not between');
		if (in_array($operator, $functionWhitelist)) {
			return $this->getClauseWithFunction($column, $operator, $v);	
		}
	}

	private function convertStringOperator($operator)
	{
		return ($this->isStringOperator($operator)) ? $this->stringOperatorsMap[$operator] : $operator;
	}

	private function isStringOperator($operator)
	{
		return isset($this->stringOperatorsMap[$operator]);
	}
	
	private function getClauseWithOperator($column, $operator, $value)
	{
		if (is_null($value)) {
			if ($operator == '!=') {
				$clause = $column.' IS NOT NULL';
			} else {
				$clause = $column.' IS NULL';
			}
			$params = [];
		} else {
			$clause = $column.' '.$operator.' ?';
			$params = [$value];
		}
		
		return [$clause, $params];
	}

	private function getClauseWithNull($column, $operator)
	{
		$clause = $column.' '.strtoupper($operator);
		$params = [];
		return [$clause, $params];
	}
	
	private function getClauseWithFunction($column, $operator, $values)
	{
		if ($operator == 'between') {
			$clause = $column . ' BETWEEN ? AND ?';
			$params = $values;
			return [$clause, $params];
		}

		if ($operator == 'not between') {
			$clause = $column . ' NOT BETWEEN ? AND ?';
			$params = $values;
			return [$clause, $params];
		}
		
		if ($this->containsSubquery($values)) {
			$placeholder = $values->getStatement();
			$values = $values->getParams();
		} else {
			$placeholder = implode(', ', array_fill(0, count($values), '?'));
		}

		if ($operator == 'in') {
			$clause = $column.' IN ('.$placeholder.')';
			
		}

		if ($operator == 'not in') {
			$clause = $column.' NOT IN ('.$placeholder.')';
		}

		$params = (array)$values;
		return [$clause, $params];
	}

	private function containsSubquery($values)
	{
		return $values instanceof Select;
	}

	private function appendPart($part)
	{
		$this->parts[] = $part;
	}

	private function appendParams($params)
	{
		$this->params = array_merge($this->params, (array)$params);
	}
}