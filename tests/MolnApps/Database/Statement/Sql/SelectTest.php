<?php

namespace MolnApps\Database\Statement\Sql;

class SelectTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated()
	{
		$select = new Select;

		$this->assertNotNull($select);
	}

	/** @test */
	public function it_builds_a_query()
	{
		$select = new Select('news');

		$this->assertEquals('SELECT * FROM news', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_columns()
	{
		$select = new Select('news', ['subject', 'body']);

		$this->assertEquals('SELECT subject, body FROM news', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_where_clause()
	{
		$select = new Select('news', [], ['id' => 55, 'body' => 'Hello']);

		$this->assertEquals('SELECT * FROM news WHERE id = ? AND body = ?', $select->getStatement());

		$this->assertEquals([55, 'Hello'], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_complex_where_clause()
	{
		$select = new Select('news', [], [
			['id', 'eq', 55], 
			['body', 'not', 'Hello']
		]);

		$this->assertEquals('SELECT * FROM news WHERE id = ? AND body != ?', $select->getStatement());

		$this->assertEquals([55, 'Hello'], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_default_order()
	{
		$select = new Select('news', [], [], ['created_at']);

		$this->assertEquals('SELECT * FROM news ORDER BY created_at ASC', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_asc_order()
	{
		$select = new Select('news', [], [], ['created_at' => 'asc']);

		$this->assertEquals('SELECT * FROM news ORDER BY created_at ASC', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_desc_order()
	{
		$select = new Select('news', [], [], ['created_at' => 'desc']);

		$this->assertEquals('SELECT * FROM news ORDER BY created_at DESC', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_limit()
	{
		$select = new Select('news', [], [], [], 10);

		$this->assertEquals('SELECT * FROM news LIMIT 10', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_limit_and_offset()
	{
		$select = new Select('news', [], [], [], 10, 20);

		$this->assertEquals('SELECT * FROM news LIMIT 10 OFFSET 20', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_specifying_offset()
	{
		$select = new Select('news', [], [], [], 0, 20);

		$this->assertEquals('SELECT * FROM news OFFSET 20', $select->getStatement());

		$this->assertEquals([], $select->getParams());
	}

	/** @test */
	public function it_builds_a_query_with_all_parameters()
	{
		$select = new Select(
			'news', ['id', 'subject', 'body'], ['id' => 55], ['created_at' => 'desc'], 20, 10
		);

		$this->assertEquals(
			'SELECT id, subject, body FROM news WHERE id = ? ORDER BY created_at DESC LIMIT 20 OFFSET 10', 
			$select->getStatement()
		);

		$this->assertEquals([
			55
		], $select->getParams());
	}
}