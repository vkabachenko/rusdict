<?php

/**
 * This is the model class for table "dct_links".
 *
 * The followings are the available columns in table 'dct_links':
 * @property integer $id
 * @property integer $id_article
 * @property integer $id_link
 *
 * The followings are the available model relations:
 * @property Articles $idLink
 * @property Articles $idArticle
 */
class Links extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dct_links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_article, id_link', 'required'),
			array('id_article, id_link', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idLink' => array(self::BELONGS_TO, 'Articles', 'id_link'),
			'idArticle' => array(self::BELONGS_TO, 'Articles', 'id_article'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_link' => 'Ссылка',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Links the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
