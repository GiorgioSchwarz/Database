<?php

namespace MolnApps\Database;

interface TableAdapter
{
	public function select(array $query = []);
	public function insert(array $assignments);
	public function update(array $assignments, array $query);
	public function delete(array $query);

	public function lastInsertId();

	public function executeSelect(Statement $statement);
	public function executeUpdate(Statement $statement);
}