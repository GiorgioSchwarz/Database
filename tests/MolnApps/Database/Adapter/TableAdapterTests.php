<?php

namespace MolnApps\Database\Adapter;

trait TableAdapterTests
{
	protected function getDatasetArray()
    {
        return [
            'people' => [
                ['first_name' => 'John', 'last_name' => 'Doe', 'age' => '28', 'job_title' => 'Coder'],
                ['first_name' => 'Jane', 'last_name' => 'Doe', 'age' => '28', 'job_title' => 'Coder']
            ],
        ];
    }

	/** @test */
    /*
    public function it_can_be_instantiated_through_factory()
	{
		$dsn = new Dsn($this->getDsn()['driver'], $this->getDsn());
		
		$tableAdapter = TableAdapterFactory::instance()->createTableAdapter($dsn, 'people');

		$this->assertNotNull($tableAdapter);
	}
	*/

	/** @test */
	public function it_selects_all_rows()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select();

		$this->assertCount(2, $rows);
		$this->assertRowMatchesDataset($rows, 0, ['first_name', 'last_name', 'age', 'job_title']);
		$this->assertRowMatchesDataset($rows, 1, ['first_name', 'last_name', 'age', 'job_title']);
	}

	/** @test */
	public function it_selects_specified_columns_only()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['columns' => ['first_name']]);

		foreach ($rows as $row) {
			$this->assertEquals(['first_name'], array_keys($row));
		}
	}

	/** @test */
	public function it_selects_all_rows_with_where_clause()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['where' => ['first_name' => 'Jane']]);

		$this->assertCount(1, $rows);
		$this->assertEquals('Jane', $rows[0]['first_name']);
	}

	/** @test */
	public function it_selects_all_rows_with_complex_where_clause()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['where' => [['first_name', 'not', 'Jane']]]);

		$this->assertCount(1, $rows);
		$this->assertEquals('John', $rows[0]['first_name']);
	}

	/** @test */
	public function it_selects_all_rows_with_a_limit()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['limit' => 1]);

		$this->assertCount(1, $rows);
		$this->assertEquals('John', $rows[0]['first_name']);
	}

	/** @test */
	public function it_selects_all_rows_with_a_limit_and_a_offset()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['limit' => 1, 'offset' => 1]);

		$this->assertCount(1, $rows);
		$this->assertEquals('Jane', $rows[0]['first_name']);
	}

	/** @test */
	public function it_selects_all_rows_with_order()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$rows = $tableAdapter->select(['order' => ['first_name']]);

		$this->assertCount(2, $rows);
		$this->assertEquals('Jane', $rows[0]['first_name']);
		$this->assertEquals('John', $rows[1]['first_name']);
	}

	/** @test */
	public function it_inserts_a_new_row()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$this->assertCount(2, $tableAdapter->select());

		$tableAdapter->insert([
			'first_name' => 'Bob', 
			'last_name' => 'Doe', 
			'age' => '49', 
			'job_title' => 'CEO'
		]);

		$this->assertEquals(3, $tableAdapter->lastInsertId());

		$this->assertCount(3, $tableAdapter->select());
	}

	/** @test */
	public function it_updates_a_row()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$this->assertCount(1, $tableAdapter->select(['where' => ['first_name' => 'John']]));

		$tableAdapter->update(['first_name' => 'John'], ['where' => ['first_name' => 'Jane']]);

		$this->assertCount(2, $tableAdapter->select(['where' => ['first_name' => 'John']]));
	}

	/** @test */
	public function it_deletes_a_row()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$this->assertCount(2, $tableAdapter->select());

		$tableAdapter->delete(['where' => ['first_name' => 'Jane']]);

		$this->assertCount(1, $tableAdapter->select());
		$this->assertEquals('John', $tableAdapter->select()[0]['first_name']);
	}

	/** @test */
	public function it_deletes_multiple_rows()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$this->assertCount(2, $tableAdapter->select());

		$tableAdapter->delete(['where' => ['last_name' => 'Doe']]);

		$this->assertCount(0, $tableAdapter->select());
	}

	/** @test */
	public function it_deletes_multiple_rows_with_a_limit()
	{
		$tableAdapter = $this->createTableAdapter('people');

		$this->assertCount(2, $tableAdapter->select());

		$tableAdapter->delete(['where' => ['last_name' => 'Doe'], 'limit' => 1]);

		$this->assertCount(1, $tableAdapter->select());
		$this->assertEquals('Jane', $tableAdapter->select()[0]['first_name']);
	}

	abstract protected function createTableAdapter($table);

	private function assertRowMatchesDataset(array $rows, $i, array $keys)
	{
		foreach ($keys as $key) {
			$this->assertEquals($this->getDatasetArray()['people'][$i][$key], $rows[$i][$key]);
		}
	}
}