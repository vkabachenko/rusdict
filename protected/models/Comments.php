<?php

/**
 * This is the model class for table "dct_comments".
 *
 * The followings are the available columns in table 'dct_comments':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $date_created
 * @property string $status
 * @property string $comment
 * @property integer $id_article
 *
 * The followings are the available model relations:
 * @property Articles $idArticle
 */
class Comments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dct_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email', 'required'),
			array('username, email', 'length', 'max'=>60),
            array('email', 'email'),
			array('comment', 'safe'),
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
			'idArticle' => array(self::BELONGS_TO, 'Articles', 'id_article'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Имя',
			'email' => 'E-mail',
			'comment' => 'Текст комментария',
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
