<?php

class m141109_074402_table_static extends CDbMigration
{
	public function up()
	{
        $this->createTable('dct_static',array(
            'id'=>'varchar(10) PRIMARY KEY',
            'title'=>'varchar(128) NOT NULL',
            'content'=>'text',
        ));
        $this->insert('dct_static',array('id'=>'index','title'=>'index'));
        $this->insert('dct_static',array('id'=>'contacts','title'=>'contacts'));
	}

	public function down()
	{
        $this->dropTable('dct_static');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}