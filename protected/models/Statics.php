<?php

/**
 * This is the model class for table "dct_static".
 *
 * The followings are the available columns in table 'dct_static':
 * @property string $id
 * @property string $title
 * @property string $content
 */
class Statics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dct_static';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title', 'length', 'max'=>128),
			array('content', 'safe'),

		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Заголовок',
			'content' => 'Содержание',
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Statics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
