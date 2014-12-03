<?php

class m141201_150931_comments_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('dct_comments',array(
            'id'=>'pk',
            'username'=>'varchar(60) NOT NULL',
            'email'=>'varchar(60) NOT NULL',
            'date_created'=>'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'status'=>'ENUM("waiting","published","banned") NOT NULL',
            'comment'=>'TEXT NULL',
            'id_article'=>'int NOT NULL'
        ));

        $this->createIndex('idArticle','dct_comments','id_article');
        $this->execute("ALTER TABLE dct_comments ADD FOREIGN KEY ( id_article ) REFERENCES dct_articles (id) ON DELETE CASCADE ON UPDATE CASCADE ;");

	}

	public function down()
	{
        $this->dropTable('dct_comments');
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