<?php

namespace MolnApps\Database\Adapter;

use \PDO;

use \Exception;

use \MolnApps\Database\TableAdapter;

use \App\Database\Sql\Statement\Statement;
use \App\Database\Sql\Statement\Insert;
use \App\Database\Sql\Statement\Select;
use \App\Database\Sql\Statement\Update;
use \App\Database\Sql\Statement\Delete;

abstract class AbstractTableAdapter implements TableAdapter
{
	private $table;
	private $pdo;

	public function __construct($table)
	{
		$this->table = $table;

		try {
			$this->pdo = $this->createPdo();
		} catch (Exception $e) {
			die('Could not connect to the database');
		}
	}

	abstract protected function createPdo();
	
	public function select(array $query)
	{
		$query = $this->normalizeQuery($query);

		$statement = new Select(
			$this->table, 
			$query['columns'], 
			$query['where'], 
			$query['order'], 
			$query['limit'], 
			$query['offset']
		);

		return $this->executeSelect($statement);
	}

	public function executeSelect(Statement $statement)
	{
		$stmt = $this->pdo->prepare($statement->getStatement());

		$result = $stmt->execute($statement->getParams());

		if ( ! $result) {
			throw new Exception($stmt->errorInfo()[2]);
		}

		return $this->fetchRows($stmt);
	}

	private function fetchRows($stmt)
	{
		$rows = [];
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}
		
		return $rows;
	}

	public function insert(array $assignments)
	{
		if ( ! $assignments) {
			return;
		}

		$statement = new Insert($this->table, $assignments);

		return $this->executeUpdate($statement);
	}

	public function update(array $assignments, array $query)
	{
		if ( ! $assignments) {
			return;
		}

		$query = $this->normalizeQuery($query);

		$statement = new Update($this->table, $assignments, $query['where']);

		return $this->executeUpdate($statement);
	}

	public function delete(array $query)
	{
		$query = $this->normalizeQuery($query);

		$statement = new Delete($this->table, $query['where']);

		return $this->executeUpdate($statement);
	}

	public function executeUpdate(Statement $statement)
	{
		$stmt = $this->pdo->prepare($statement->getStatement());

		$result = $stmt->execute($statement->getParams());

		if ( ! $result) {
			throw new Exception($stmt->errorInfo()[2]);
		}
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	private function normalizeQuery(array $query)
	{
		if ( ! isset($query['columns'])) {
			$query['columns'] = ['*'];
		}
		
		if ( ! isset($query['where'])) {
			$query['where'] = [];
		}
		
		if ( ! isset($query['order'])) {
			$query['order'] = [];
		}
		
		if ( ! isset($query['limit'])) {
			$query['limit'] = null;
		}
		
		if ( ! isset($query['offset'])) {
			$query['offset'] = null;
		}

		return $query;
	}
}