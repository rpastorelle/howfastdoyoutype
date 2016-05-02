<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $this->table('users')
             ->addColumn('username', 'string', ['limit' => 255, 'null' => false])
             ->addColumn('timestamp', 'integer', ['null' => false])
             ->create();
    }
}
