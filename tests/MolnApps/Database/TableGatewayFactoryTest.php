<?php

namespace MolnApps\Database;

use \MolnApps\Database\Dsn;

class TableGatewayFactoryTest extends \PHPUnit_Framework_TestCase
{
	protected function tearDown()
	{
		TableGatewayFactory::reset();
	}

	/** @test */
	public function it_creates_a_table_gateway()
	{
		$tableGateway = TableGatewayFactory::create('people', new Dsn('memory'));

		$this->assertInstanceOf(TableGateway::class, $tableGateway);
	}

	/** @test */
	public function it_registers_a_table_gateway_and_returns_it_upon_request()
	{
		$tableGatewayInstance = TableGatewayFactory::create('people', new Dsn('memory'));
		
		TableGatewayFactory::register('people', $tableGatewayInstance);

		$tableGateway = TableGatewayFactory::get('people');

		$this->assertEquals($tableGatewayInstance, $tableGateway);
	}
}