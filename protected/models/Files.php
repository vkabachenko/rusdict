<?php

/**
 * This is the model class for table "dct_files".
 *
 * The followings are the available columns in table 'dct_files':
 * @property integer $id
 * @property string $id_article
 * @property string $title
 * @property string $document
 * @property string $filename
 */
class Files extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dct_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

            array('document','file','types'=>'doc,docx,odt,pdf,ppt,pptx',
                'allowEmpty'=>false),
            array('title','length', 'max'=>250),

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
			'title' => 'Описание материала',
			'document' => 'Материал',
		);
	}


    protected function beforeSave(){
        if(!parent::beforeSave())
            return false;
        if ($document=CUploadedFile::getInstance($this,'document')){
            $this->id_article = Yii::app()->session['article'];
            $this->filename = uniqid();
            $this->document=$document;
            $this->document->saveAs(
                Yii::getPathOfAlias('webroot.files').DIRECTORY_SEPARATOR.$this->filename);
        }
        return true;
    }


    protected function beforeDelete(){
        if(!parent::beforeDelete())
            return false;
        $this->deleteDocument(); // удалили модель? удаляем и файл
        return true;
    }

    public function deleteDocument(){
        $documentPath=Yii::getPathOfAlias('webroot.files').DIRECTORY_SEPARATOR.
            $this->filename;
        if(is_file($documentPath))
            unlink($documentPath);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Files the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
