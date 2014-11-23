<?php

class m141123_152726_add_pure_title extends CDbMigration
{
	public function up()
	{
        $this->addColumn('dct_articles','pure_title','varchar(128)');
	}

	public function down()
	{
        $this->dropColumn('dct_articles','pure_title');

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