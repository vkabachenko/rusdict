<?php

class m141209_135917_table_abbrev extends CDbMigration
{
	public function up()
	{
        $this->createTable('dct_abbrev',array(
            'abbrev'=>'varchar(20) PRIMARY KEY',
            'text'=>'varchar(255) NOT NULL'
        ));

	}

	public function down()
	{
        $this->dropTable('dct_abbrev');
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