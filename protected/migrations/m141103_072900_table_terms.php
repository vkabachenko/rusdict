<?php

class m141103_072900_table_terms extends CDbMigration
{
	public function up()
	{
        $this->createTable('dct_terms',array(
            'id'=>'pk',
            'term'=>'varchar(128) NOT NULL',
            'id_article'=>'int NOT NULL'
        ));

        $this->createIndex('idArticle','dct_terms','id_article');

        // Ошибка MySQL при формировании связи
//        $this->addForeignKey('fArticle','dct_terms','id_article','dct_article','id','CASCADE','CASCADE');
	}

	public function down()
	{
		$this->dropIndex('idArticle','dct_terms');
        $this->dropTable('dct_terms');


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