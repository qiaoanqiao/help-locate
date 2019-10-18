<?php

use Phinx\Migration\AbstractMigration;

class RelationConfigMigration extends AbstractMigration
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
        $relationshipBetweenExtensionSet = $this->table('relation_config');
        $relationshipBetweenExtensionSet->addColumn('relation_id', 'integer',  ['limit' => 11, 'default' => 0])
            ->addColumn('user_id', 'integer',  ['limit' => 11, 'default' => 0])
            ->addColumn('shar_position', 'boolean',  ['default' => 0])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['user_id', 'relation_id'])
            ->create();


    }
}
