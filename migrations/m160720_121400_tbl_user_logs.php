<?php

use yii\db\Migration;
use yii\db\Schema;

class m160720_121400_tbl_user_logs extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_logs}}', [
            'id' => Schema::TYPE_PK,
            'id_user'=> Schema::TYPE_INTEGER,
            'event_text' => Schema::TYPE_STRING . ' NOT NULL',
            'date_create'=>Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'date_update'=>Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], $tableOptions);

           $this->addForeignKey(
               '{{%id_user}}',
               '{{%user_logs}}',
               'id_user',
               '{{%user}}',
               'id',
               'CASCADE',
               'CASCADE'
           );
    }

    public function down()
    {
        $this->dropTable('{{%user_logs}}');
    }

}
