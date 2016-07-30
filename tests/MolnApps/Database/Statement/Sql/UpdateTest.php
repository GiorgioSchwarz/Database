<?php

namespace MolnApps\Database\Statement\Sql;

class UpdateTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated_with_table_and_assignments_and_where_clause()
	{
		$insert = new Update(
			'news', 
			['subject' => 'My subject', 'body' => 'Lorem ipsum dolor'], 
			['id' => 55]
		);

		$this->assertEquals('UPDATE news SET subject = ?, body = ? WHERE id = ?', $insert->getStatement());
		$this->assertEquals(['My subject', 'Lorem ipsum dolor', 55], $insert->getParams());
	}

	/** @test */
	public function it_will_create_invalid_query_if_no_where_clause_is_provided()
	{
		$insert = new Update(
			'news', 
			['subject' => 'My subject', 'body' => 'Lorem ipsum dolor'], 
			[]
		);

		$this->assertEquals('UPDATE news SET subject = ?, body = ? WHERE ', $insert->getStatement());
		$this->assertEquals(['My subject', 'Lorem ipsum dolor'], $insert->getParams());
	}

	/** @test */
	public function it_will_create_invalid_query_if_no_assignment_is_provided()
	{
		$insert = new Update(
			'news', 
			[], 
			['id' => 55]
		);

		$this->assertEquals('UPDATE news SET  WHERE id = ?', $insert->getStatement());
		$this->assertEquals([55], $insert->getParams());
	}
}