<?php

namespace MolnApps\Database\Statement\Sql;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated_with_table_and_assignments_and_where_clause()
	{
		$insert = new Delete('news', ['id' => 55]);

		$this->assertEquals('DELETE FROM news WHERE id = ?', $insert->getStatement());
		$this->assertEquals([55], $insert->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_table_and_assignments_and_complex_where_clause()
	{
		$insert = new Delete('news', [['id', 'not', 55]]);

		$this->assertEquals('DELETE FROM news WHERE id != ?', $insert->getStatement());
		$this->assertEquals([55], $insert->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_table_and_assignments_and_mixed_where_clause()
	{
		$insert = new Delete('news', ['id' => 15, ['subject', 'not', 'hello']]);

		$this->assertEquals('DELETE FROM news WHERE id = ? AND subject != ?', $insert->getStatement());
		$this->assertEquals([15, 'hello'], $insert->getParams());
	}

	/** @test */
	public function it_will_create_invalid_query_if_no_where_clause_is_provided()
	{
		$insert = new Delete('news', []);

		$this->assertEquals('DELETE FROM news WHERE', $insert->getStatement());
		$this->assertEquals([], $insert->getParams());
	}
}