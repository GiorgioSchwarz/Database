<?php

namespace MolnApps\Database\Statement\Sql;

class Delete extends AbstractStatement
{
	public function __construct($table, array $where, $limit = 0)
	{
		list ($whereClause, $whereParams) = $this->getWhereClause($where, ' AND ');

		$stmt = trim("DELETE FROM {$table} WHERE {$whereClause}");

		if ($limit) {
			$stmt.= " LIMIT ".(int)$limit;
		}

		$params = $whereParams;
		
		$this->setStatement($stmt);
		$this->setParams($params);
	}
	private function getWhereClause($values, $combinedBy = ',')
	{
		$whereClause = new WhereClause($values, $combinedBy);
		return [$whereClause->getClause(), $whereClause->getParams()];
	}
}