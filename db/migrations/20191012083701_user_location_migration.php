<?php

use Phinx\Migration\AbstractMigration;

class UserLocationMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
// create the table
        $table = $this->table('user_location');
        $table->addColumn('locate_mode', 'string',  ['limit' => 30, 'default' => ''])
            ->addColumn('latitude', 'string',  ['limit' => 50, 'default' => ''])
            ->addColumn('longitude', 'string',  ['limit' => 50, 'default' => ''])
            ->addColumn('accuracy', 'string',  ['limit' => 50, 'default' => ''])
            ->addColumn('verticalAccuracy', 'string',  ['limit' => 255, 'default' => ''])
            ->addColumn('type', 'string',  ['limit' => 10, 'default' => 'gcj02'])
            ->addColumn('speed', 'string',  ['limit' => 10, 'default' => ''])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
