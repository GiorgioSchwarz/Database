<?php

namespace MolnApps\Database\Statement\Sql;

class Update extends AbstractStatement
{
	public function __construct($table, array $values, array $where)
	{
		list ($assignments, $assignmentParams) = $this->getAssignments($values, ', ');
		list ($whereClause, $whereParams) = $this->getWhereClause($where, ' AND ');

		$stmt = "UPDATE {$table} SET {$assignments} WHERE {$whereClause}";
		
		$params = array_merge($assignmentParams, $whereParams);

		$this->setStatement($stmt);
		$this->setParams($params);
	}

	private function getAssignments($values, $combinedBy = ',')
	{
		$arr = array();
		foreach ($values as $column => $value) {
			$arr[] = $column.' = ?';
		}
		return [implode($combinedBy, $arr), array_values($values)];
	}
	
	private function getWhereClause($where, $combinedBy)
	{
		$whereClause = new WhereClause($where, $combinedBy);
		return [$whereClause->getClause(), $whereClause->getParams()];
	}
}