<?php

use yii\db\Migration;

class m180719_074621_crete_kl83_file_set_table extends Migration
{
    const TABLE_NAME = '{{%kl83_file_set}}';

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
        ]);
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        $this->dropTable(self::TABLE_NAME);
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
