<?php

namespace MolnApps\Database;

use \MolnApps\Database\Container\Container;

use \MolnApps\Database\Dsn;

class TableGatewayFactoryTest extends \PHPUnit_Framework_TestCase
{
	/** @before */
	protected function setUpInstance()
	{
		$this->factory = Container::get('tableGatewayFactory');
	}

	protected function tearDown()
	{
		Container::reset();
	}

	/** @test */
	public function it_creates_a_table_gateway()
	{
		$tableGateway = $this->factory->createTable('people', new Dsn('memory'));

		$this->assertInstanceOf(TableGateway::class, $tableGateway);
	}

	/** @test */
	public function it_registers_a_table_gateway_and_returns_it_upon_request()
	{
		$tableGatewayInstance = $this->factory->createTable('people', new Dsn('memory'));
		
		$this->factory->registerTable('people', $tableGatewayInstance);

		$tableGateway = $this->factory->getTable('people');

		$this->assertEquals($tableGatewayInstance, $tableGateway);
	}
}