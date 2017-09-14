<?php

use yii\db\Schema;
use yii\db\Migration;

class m170719_080151_crete_kl83_file_table extends Migration
{
    const TABLE_NAME = 'kl83_file';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => Schema::TYPE_UPK,
            'idx' => Schema::TYPE_INTEGER . ' unsigned NOT NULL',
            'fileSetId' => Schema::TYPE_INTEGER . ' unsigned NOT NULL',
            'createdAt' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'createdBy' => Schema::TYPE_INTEGER . ' unsigned NOT NULL',
            'path' => $this->string(2500) . ' NOT NULL',
            'url' => $this->string(2500) . ' NOT NULL',
        ]);
        $this->createIndex('fileSetId', self::TABLE_NAME, 'fileSetId');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
