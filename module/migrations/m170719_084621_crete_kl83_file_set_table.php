<?php

use yii\db\Schema;
use yii\db\Migration;

class m170719_084621_crete_kl83_file_set_table extends Migration
{
    const TABLE_NAME = 'kl83_file_set';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => Schema::TYPE_UPK,
            'createdAt' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'createdBy' => Schema::TYPE_INTEGER . ' unsigned NOT NULL',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
