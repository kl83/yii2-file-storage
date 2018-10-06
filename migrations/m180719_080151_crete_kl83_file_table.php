<?php

use yii\db\Migration;

class m180719_080151_crete_kl83_file_table extends Migration
{
    const TABLE_NAME = '{{%kl83_file}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this
                ->primaryKey()
                ->unsigned(),
            'createdAt' => $this
                ->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP'),
            'createdById' => $this
                ->integer()
                ->unsigned(),
            'idx' => $this
                ->integer()
                ->unsigned()
                ->defaultValue(4294967295)
                ->notNull(),
            'fileSetId' => $this
                ->integer()
                ->unsigned(),
            'relPath' => $this
                ->string(5000)
                ->notNull(),
        ]);
        $this->createIndex(
            'fileSetId',
            self::TABLE_NAME,
            'fileSetId'
        );
        $this->addForeignKey(
            'fileSet',
            self::TABLE_NAME,
            'fileSetId',
            '{{%kl83_file_set}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        $this->dropTable(self::TABLE_NAME);
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
