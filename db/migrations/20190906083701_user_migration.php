<?php

use Phinx\Migration\AbstractMigration;

class UserMigration extends AbstractMigration
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
        $table = $this->table('user');
        $table->addColumn('name', 'string',  ['limit' => 30, 'default' => ''])
            ->addColumn('note_name', 'string',  ['limit' => 30, 'default' => ''])
            ->addColumn('mobile', 'string',  ['limit' => 30, 'default' => ''])
            ->addColumn('password', 'string',  ['limit' => 125, 'default' => ''])
            ->addColumn('email', 'string',  ['limit' => 40, 'default' => ''])
            ->addColumn('wx_id', 'string',  ['limit' => 100, 'default' => ''])
            ->addColumn('is_vip', 'boolean', ['default' => 0])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
