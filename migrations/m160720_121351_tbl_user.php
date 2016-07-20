<?php

use yii\db\Migration;
use yii\db\Schema;

class m160720_121351_tbl_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'avatar' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'token' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'about_me' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'status' => Schema::TYPE_BOOLEAN . ' DEFAULT 0 ',
            'date_create'=>Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'date_update'=>Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ], $tableOptions);
        $this->createIndex('email', '{{%user}}', 'email', true);

    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }

}
