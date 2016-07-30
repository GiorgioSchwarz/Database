<?php

namespace MolnApps\Database\Statement\Sql;

class Select extends AbstractStatement
{
	private $columns = [];
	private $table;
	private $join;
	private $using;
	private $where = [];
	private $orderBy = [];
	private $limit;
	private $offset;

	public function __construct(
		$table = null, 
		array $columns = [], 
		array $where = [], 
		array $orderBy = [], 
		$limit = null, 
		$offset = null)
	{
		$this
			->from($table)
			->columns($columns)
			->where($where)
			->orderBy($orderBy)
			->limit($limit)
			->offset($offset);
	}

	public function columns(array $columns)
	{
		$this->columns = $columns;
		return $this;
	}
	
	public function from($table)
	{
		$this->table = $table;
		return $this;
	}
	
	public function join($table)
	{
		$this->join = $table;
		return $this;
	}
	
	public function using($columns)
	{
		if (!is_array($columns)) {
			$columns = [$columns];
		}
		$this->using = $columns;
		return $this;
	}
	
	public function where(array $where)
	{
		$this->where = $where;
		return $this;
	}
	
	public function orderBy(array $orderBy)
	{
		$this->orderBy = $orderBy;
		return $this;
	}
	
	public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}
	
	public function offset($offset)
	{
		$this->offset = $offset;
		return $this;
	}
	
	protected function createStatement()
	{
		$columns = $this->getColumns();
		$stmt = "SELECT {$columns} FROM {$this->table}";
		
		if ($this->join) {
			$stmt.= " JOIN {$this->join}";
		}
		if ($this->using) {
			$using = implode(', ', $this->using);
			$stmt.= " USING ({$using})";
		}

		list ($whereAssignments, $params) = $this->getWhereAssignments(' AND ');
		if ($this->where) {
			$stmt.= " WHERE {$whereAssignments}";
		}

		$orderByAssignments = $this->getOrderByAssignments();
		if ($orderByAssignments) {
			$stmt.= " ORDER BY {$orderByAssignments}";
		}
		if ($this->limit) {
			$stmt.= " LIMIT {$this->limit}";
		}
		if ($this->offset) {
			$stmt.= " OFFSET {$this->offset}";
		}
		
		$this->setStatement($stmt);
		$this->setParams($params);
	}
	
	private function getColumns()
	{
		if (!$this->columns) {
			return '*';
		}
		return implode(', ', $this->columns);
	}
	
	private function getOrderByAssignments()
	{
		$arr = array();
		foreach ($this->orderBy as $column => $order) {
			if (is_numeric($column)) {
				$column = $order;
				$order = 'asc';
			}
			$arr[] = $column.' '.strtoupper($order);
		}
		return implode(', ', $arr);
	}
	
	private function getWhereAssignments($combinedBy)
	{
		$whereAssignments = new WhereClause($this->where, $combinedBy);
		return [$whereAssignments->getClause(), $whereAssignments->getParams()];
	}
}