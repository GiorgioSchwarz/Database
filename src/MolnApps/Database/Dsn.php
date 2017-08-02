<?php

namespace MolnApps\Database;

class Dsn implements PdoDsn, DsnDriver
{
	private $driver;
	private $pdo;

	public function __construct($driver, \Pdo $pdo = null)
	{
		$this->driver = $driver;
		$this->pdo = $pdo;
	}

	public function getDriver()
	{
		return $this->driver;
	}

	public function getPdo()
	{
		return $this->pdo;
	}
}