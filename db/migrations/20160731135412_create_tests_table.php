<?php

use Phinx\Migration\AbstractMigration;

class CreateTestsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('people');

        $table->addColumn('first_name', 'string');
        $table->addColumn('last_name', 'string');
        $table->addColumn('age', 'integer');
        $table->addColumn('job_title', 'string');

        $table->addTimestamps();

        $table->create();
    }
}
