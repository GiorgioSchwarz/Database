<?php

namespace MolnApps\Database\Statement\Sql;

class Insert extends AbstractStatement
{
	private $params = [];
	private $values = [];
	private $onDuplicateKey = [];
	
	public function __construct($table, array $values, array $onDuplicateKey = array())
	{
		$this->values = $values;
		$this->onDuplicateKey = $onDuplicateKey;
		
		$stmt = "INSERT INTO {$table} ({$this->getProperties()}) VALUES ({$this->getValuePlaceholders()})";
		$this->getValueParams();
		
		if ($onDuplicateKey) {
			 $stmt.= " ON DUPLICATE KEY UPDATE {$this->getOnDuplicateKeyAssignments()}";
			 $this->getOnDuplicateKeyAssignmentsParams();
		}
		
		$this->setStatement($stmt);
		$this->setParams($this->params);
	}
	private function getProperties()
	{
		return implode(', ', array_keys($this->values));
	}
	private function getValuePlaceholders()
	{
		return implode(', ', array_fill(0, count($this->values), '?'));
	}
	private function getValueParams()
	{
		foreach ($this->values as $value) {
			$this->addParam($value);
		}
	}
	private function getOnDuplicateKeyAssignments()
	{
		$assignments = [];
		foreach ($this->onDuplicateKey as $column => $value) {
			$assignments[] = $column.' = ?';
		}
		return implode(', ', $assignments);
	}
	private function getOnDuplicateKeyAssignmentsParams()
	{
		foreach ($this->onDuplicateKey as $column => $value) {
			$this->addParam($value);
		}
	}
	private function addParam($value)
	{
		$this->params[] = $value;
	}
}