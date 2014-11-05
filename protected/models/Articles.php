<?php

/**
 * This is the model class for table "dct_articles".
 *
 * The followings are the available columns in table 'dct_articles':
 * @property integer $id
 * @property string $id_letter
 * @property string $title
 * @property string $article
 * @property string $terms
 * @property integer $id_section
 *
 * The followings are the available model relations:
 * @property Letters $idLetter
 * @property Sections $idSection
 */
class Articles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dct_articles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_letter, id_section, title', 'required'),
			array('id_letter', 'length', 'max'=>1),
			array('title', 'length', 'max'=>128),
            array('title','checkFirstLetter'), // 1-й символ - буква кириллицы
			array('article, terms', 'safe'),
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
			'idLetter' => array(self::BELONGS_TO, 'Letters', 'id_letter'),
            'idSection' => array(self::BELONGS_TO, 'Sections', 'id_section'),
            'files' => array(self::HAS_MANY, 'Files', 'id_article'),
            'terms' => array(self::HAS_MANY, 'Terms', 'id_article'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id_section' => 'Раздел',
			'title' => 'Заголовок',
			'article' => 'Статья',
            'terms' => 'Термины (через запятую)',
		);
	}

    protected function beforeSave(){
        if(!parent::beforeSave())
            return false;

        // удаление всех терминов, связанных с данной статьей
        Terms::model()->deleteAllByAttributes(array('id_article'=>$this->id));

        $this->fillTerms();

        return true;
    }


    /*
     * Заполнение таблицы Terms для данной статьи
     */
    public function fillTerms() {

        // добавление термина из заголовка статьи
        $record = new Terms();
        $record->id_article = $this->id;
        $record->term = $this->delAccent($this->title);
        $record->save();

        // добавление терминов из списка терминов статьи
        $terms = explode(',',$this->terms);
        foreach($terms as $term) {
            $term = trim($term);
            if ($term) {
                $record = new Terms();
                $record->id_article = $this->id;
                $record->term = $this->delAccent($term);
                $record->save();
            }
        }

    }

        /*
         * Правила валидации
         */

    public function checkFirstLetter($attr,$params) {

        $letter = Utf8::mb_firstLetter($this->title);
        if ($letter < 'А' || $letter > 'Я') {
            $this->addError('title',
                'Первым символом должна быть буква кириллицы');
        }
    }


    /*
     * Преобразование к нижнему регистру и удаление знака ударения
     */
    public function delAccent($str) {

        $str = mb_strtolower($str,'utf-8');

        $accent = Utf8::codeToUtf8(769); // знак ударения
        $pattern = array('á','é','ó','ό',$accent);
        $replacement = array('а','е','о','о','');

        for ($i=0; $i<sizeof($pattern); $i++) {
            $str = mb_ereg_replace($pattern[$i], $replacement[$i], $str);
        }

        return $str;
    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Articles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
