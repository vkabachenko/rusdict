<?php

class m151216_172344_longer_term extends CDbMigration
{
	public function up()
	{
             $this->alterColumn('dct_articles','terms','text');
	}

	public function down()
	{
             $this->alterColumn('dct_articles','terms','tinytext');
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
