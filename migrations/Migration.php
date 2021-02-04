<?php

namespace app\migrations;

use yii\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    /**
     * @var string
     */
    protected $tableOptions = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
    }
}
