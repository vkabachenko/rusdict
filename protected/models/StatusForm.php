<?php

/**
 * Форма выбора статуса комментариев,
 * отображаемых в списке меню администратора Комментарии
 */
class StatusForm extends CFormModel
{
    public $idStatus;


    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('idStatus', 'checkStatus'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'idStatus'=>'Выберите статус комментария',
        );
    }

 /*
 * Правила валидации
 */

    public function checkStatus($attr,$params) {

        if (!Status::model()->findByPk($this->$attr)) {
            $this->addError($attr,
                'Неверный статус комментария');
        }
    }

    }
?>