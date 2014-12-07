<?php

class m141204_053257_add_status_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('dct_status',array(
            'id'=>'pk',
            'name'=>'varchar(30) NOT NULL',
        ));
        $this->insert('dct_status',array('name'=>'Ожидание',));
        $this->insert('dct_status',array('name'=>'Опубликовано',));
        $this->insert('dct_status',array('name'=>'Запрещено',));

        $this->addColumn('dct_comments','id_status','integer');
        $this->execute("ALTER TABLE dct_comments ADD FOREIGN KEY ( id_status ) REFERENCES dct_status (id) ON DELETE CASCADE ON UPDATE CASCADE ;");
        $this->dropColumn('dct_comments','status');

    }

	public function down()
	{

        $this->dropForeignKey('dct_comments_ibfk_2','dct_comments');
        $this->dropColumn('dct_comments','id_status');
        $this->dropTable('dct_status');
        $this->addColumn('dct_comments','status','ENUM("waiting","published","banned") NOT NULL');

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