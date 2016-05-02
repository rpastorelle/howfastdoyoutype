<?php

use Phinx\Migration\AbstractMigration;

class CreateStatsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('stats')
             ->addColumn('phrase_id', 'integer', ['null' => false])
             ->addColumn('user_id', 'integer', ['null' => false])
             ->addColumn('milliseconds', 'integer', ['null' => false])
             ->addColumn('wpm', 'float', ['precision' => 7, 'scale' => 3, 'null' => false])
             ->addColumn('nwpm', 'float', ['precision' => 7, 'scale' => 3, 'null' => false])
             ->addColumn('errors', 'integer', ['limit' => 4, 'null' => false])
             ->addColumn('color', 'string', ['limit' => 20, 'null' => false])
             ->addColumn('isMobile', 'integer', ['limit' => 1, 'default' => 0])
             ->addColumn('isTablet', 'integer', ['limit' => 1, 'default' => 0])
             ->addColumn('timestamp', 'integer', ['null' => false])
             ->addColumn('isDNQ', 'integer', ['limit' => 1, 'default' => 0])
             ->addIndex('phrase_id')
             ->addIndex('user_id')
             ->addIndex('color')
             ->addIndex('isMobile')
             ->addIndex('isTablet')
             ->addIndex('isDNQ')
             ->create();
    }
}
