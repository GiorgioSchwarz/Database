<?php

namespace MolnApps\Database;

use \MolnApps\Database\Dsn;
use \MolnApps\Database\Container\Container;

class TableGatewayTest extends \PHPUnit_Framework_TestCase
{
	private $tableGatewayFactory;
	private $table;

	protected function setUp()
	{
		$this->tableGatewayFactory = Container::get('tableGatewayFactory');

		// TableGateway can be created through static constructor
		$tableGatewayInstance = $this->tableGatewayFactory->createTable('people', new Dsn('memory'));
		
		// A TableGateway instance can be registered to be accessed as a singleton
		// through the TableGateway::get('table') method.
		$this->tableGatewayFactory->registerTable('people', $tableGatewayInstance);
	}

	protected function tearDown()
	{
		Container::reset();
	}

	/** @test */
	public function it_inserts_a_record_and_returns_last_insert_id()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->table->insert(['firstName' => 'John', 'lastName' => 'Doe']);
		$this->assertEquals(1, $this->table->lastInsertId());

		$this->table->insert(['firstName' => 'Jane', 'lastName' => 'Doe']);
		$this->assertEquals(2, $this->table->lastInsertId());
	}

	/** @test */
	public function it_updates_a_record()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->insertRows([
			['firstName' => 'John', 'lastName' => 'Doe'],
			['firstName' => 'Jane', 'lastName' => 'Doe'],
			['firstName' => 'Bob', 'lastName' => 'Doe'],
		]);

		$this->table->update(['firstName' => 'Jane'], ['firstName' => 'John']);

		$this->assertEquals([
			['id' => 1, 'firstName' => 'Jane', 'lastName' => 'Doe'],
			['id' => 2, 'firstName' => 'Jane', 'lastName' => 'Doe'],
			['id' => 3, 'firstName' => 'Bob', 'lastName' => 'Doe']
		], $this->table->select());
	}

	/** @test */
	public function it_deletes_a_record()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->insertRows([
			['firstName' => 'John', 'lastName' => 'Doe'],
			['firstName' => 'Jane', 'lastName' => 'Doe'],
			['firstName' => 'Bob', 'lastName' => 'Doe'],
		]);

		$this->assertCount(3, $this->table->select());

		$this->table->delete(['firstName' => 'John']);

		$this->assertCount(2, $this->table->select());
	}

	/** @test */
	public function it_selects_all_records()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->insertRows([
			['firstName' => 'John', 'lastName' => 'Doe'],
			['firstName' => 'Jane', 'lastName' => 'Doe'],
			['firstName' => 'Bob', 'lastName' => 'Doe'],
		]);

		$result = $this->table->select();

		$this->assertEquals([
			['id' => 1, 'firstName' => 'John', 'lastName' => 'Doe'],
			['id' => 2, 'firstName' => 'Jane', 'lastName' => 'Doe'],
			['id' => 3, 'firstName' => 'Bob', 'lastName' => 'Doe'],
		], $result);
	}

	/** @test */
	public function it_selects_records_with_where_clause()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->insertRows([
			['firstName' => 'John', 'lastName' => 'Doe'],
			['firstName' => 'Jane', 'lastName' => 'Doe'],
			['firstName' => 'Bob', 'lastName' => 'Doe'],
		]);

		$result = $this->table->select(['id' => 2]);

		$this->assertEquals([
			['id' => 2, 'firstName' => 'Jane', 'lastName' => 'Doe']
		], $result);
	}

	/** @test */
	public function it_selects_records_with_query()
	{
		$this->table = $this->tableGatewayFactory->getTable('people');

		$this->insertRows([
			['firstName' => 'John', 'lastName' => 'Doe'],
			['firstName' => 'Jane', 'lastName' => 'Doe'],
			['firstName' => 'Bob', 'lastName' => 'Doe'],
		]);
		
		$result = $this->table->select([
			'columns' => ['firstName'], 
			'where' => ['lastName' => 'Doe'],
			'limit' => 1,
			'offset' => 1,
			'order' => ['firstName' => 'asc'],
		]);

		$this->assertEquals([
			['firstName' => 'Jane']
		], $result);
	}

	private function insertRows(array $rows)
	{
		foreach ($rows as $row) {
			$this->table->insert($row);
		}
	}
}