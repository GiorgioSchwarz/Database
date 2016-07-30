<?php

namespace MolnApps\Database\Statement\Sql;

class InsertTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated_with_table_and_assignments()
	{
		$insert = new Insert('news', ['subject' => 'My subject', 'body' => 'Lorem ipsum dolor']);

		$this->assertEquals('INSERT INTO news (subject, body) VALUES (?, ?)', $insert->getStatement());
		$this->assertEquals(['My subject', 'Lorem ipsum dolor'], $insert->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_table_and_no_assignments()
	{
		$insert = new Insert('news', []);

		$this->assertEquals('INSERT INTO news () VALUES ()', $insert->getStatement());
		$this->assertEquals([], $insert->getParams());
	}
}