<?php

use Phinx\Migration\AbstractMigration;

class EquipmentRecordMigration extends AbstractMigration
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
        $table = $this->table('equipment_records');
        $table->addColumn('device_name', 'integer')
            ->addColumn('imei', 'string',  ['limit' => 256, 'default' => '']) //android 标识
            ->addColumn('idfa', 'string',  ['limit' => 30, 'default' => '']) //ios 标识
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']) //设备创建时间
            ->create();
    }
}
