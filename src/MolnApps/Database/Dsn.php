<?php

namespace MolnApps\Database;

class Dsn
{
	private $driver;
	private $dsn = [];

	public function __construct($driver, array $dsn = [])
	{
		$this->driver = $driver;
		$this->dsn = $dsn;
	}

	public function getDriver()
	{
		return $this->driver;
	}

	public function getDsn()
	{
		return $this->dsn;
	}
}