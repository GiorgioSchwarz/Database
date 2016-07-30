<?php

namespace MolnApps\Database\Statement\Sql;

use \MolnApps\Database\Statement;

class AbstractStatement implements Statement
{
	private $stmt;
	private $params;

	protected function setStatement($stmt)
	{
		$this->stmt = $stmt;
	}
	protected function setParams(array $params)
	{
		$this->params = $params;
	}

	public function getStatement()
	{
		if ( ! $this->stmt) {
			$this->createStatement();
		}
		return $this->stmt;
	}
	public function getParams()
	{
		return $this->params;
	}

	// Override to lazily create a statement
	protected function createStatement()
	{

	}

	public function __toString()
	{
		echo '<p>'.$this->getStatement().'</p>';
		echo '<pre>';
		print_r($this->getParams());
		echo '</pre>';
		return '';
	}
}