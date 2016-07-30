<?php

namespace MolnApps\Database\Statement\Sql;

class WhereClauseTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated_with_simple_array()
	{
		$where = new WhereClause(['id' => 55, 'body' => 'hello']);

		$this->assertEquals('id = ? AND body = ?', $where->getClause());
		$this->assertEquals([55, 'hello'], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_complex_array()
	{
		$where = new WhereClause([['id', 'eq', 55], ['body', 'not', 'hello']]);

		$this->assertEquals('id = ? AND body != ?', $where->getClause());
		$this->assertEquals([55, 'hello'], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_mixed_array()
	{
		$where = new WhereClause(['id' => 55, ['body', 'not', 'hello']]);

		$this->assertEquals('id = ? AND body != ?', $where->getClause());
		$this->assertEquals([55, 'hello'], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_eq_keyword()
	{
		$where = new WhereClause([['id', 'eq', 55]]);

		$this->assertEquals('id = ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_not_keyword()
	{
		$where = new WhereClause([['id', 'not', 55]]);

		$this->assertEquals('id != ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_gt_keyword()
	{
		$where = new WhereClause([['id', 'gt', 55]]);

		$this->assertEquals('id > ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_gte_keyword()
	{
		$where = new WhereClause([['id', 'gte', 55]]);

		$this->assertEquals('id >= ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_lt_keyword()
	{
		$where = new WhereClause([['id', 'lt', 55]]);

		$this->assertEquals('id < ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_lte_keyword()
	{
		$where = new WhereClause([['id', 'lte', 55]]);

		$this->assertEquals('id <= ?', $where->getClause());
		$this->assertEquals([55], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_null()
	{
		$where = new WhereClause([['id', 'eq', null]]);

		$this->assertEquals('id IS NULL', $where->getClause());
		$this->assertEquals([], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_not_null()
	{
		$where = new WhereClause([['id', 'not', null]]);

		$this->assertEquals('id IS NOT NULL', $where->getClause());
		$this->assertEquals([], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_in()
	{
		$where = new WhereClause([['id', 'in', ['a', 'b', 'c']]]);

		$this->assertEquals('id IN (?, ?, ?)', $where->getClause());
		$this->assertEquals(['a', 'b', 'c'], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_not_in()
	{
		$where = new WhereClause([['id', 'not in', ['a', 'b', 'c']]]);

		$this->assertEquals('id NOT IN (?, ?, ?)', $where->getClause());
		$this->assertEquals(['a', 'b', 'c'], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_between()
	{
		$where = new WhereClause([['id', 'between', [0, 25]]]);

		$this->assertEquals('id BETWEEN ? AND ?', $where->getClause());
		$this->assertEquals([0, 25], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_not_between()
	{
		$where = new WhereClause([['id', 'not between', [15, 25]]]);

		$this->assertEquals('id NOT BETWEEN ? AND ?', $where->getClause());
		$this->assertEquals([15, 25], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_equals_sign()
	{
		$where = new WhereClause([['id', '=', 15]]);

		$this->assertEquals('id = ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_not_equals_sign()
	{
		$where = new WhereClause([['id', '!=', 15]]);

		$this->assertEquals('id != ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_greater_than_sign()
	{
		$where = new WhereClause([['id', '>', 15]]);

		$this->assertEquals('id > ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_greater_than_equals_to_sign()
	{
		$where = new WhereClause([['id', '>=', 15]]);

		$this->assertEquals('id >= ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_less_than_sign()
	{
		$where = new WhereClause([['id', '<', 15]]);

		$this->assertEquals('id < ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_can_be_instantiated_with_less_than_equals_to_sign()
	{
		$where = new WhereClause([['id', '<=', 15]]);

		$this->assertEquals('id <= ?', $where->getClause());
		$this->assertEquals([15], $where->getParams());
	}

	/** @test */
	public function it_cannot_be_instantiated_with_invalid_sign()
	{
		$where = new WhereClause([['id', '<>', 15]]);

		$this->assertEquals('', $where->getClause());
		$this->assertEquals([], $where->getParams());
	}

	/** @test */
	public function it_cannot_be_instantiated_with_invalid_keyword()
	{
		$where = new WhereClause([['id', 'foo', 15]]);

		$this->assertEquals('', $where->getClause());
		$this->assertEquals([], $where->getParams());
	}
}