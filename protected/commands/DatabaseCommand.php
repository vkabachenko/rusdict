<?php



class DatabaseCommand extends CConsoleCommand {
/* @var $model User */

    /*
     * Смена административного пароля
     * Параметры:
     * -- старый пароль
     * -- новый пароль
     * Вызов: php yiic database password <oldpassword> <newpassword>
     */
    public function ActionPassword($args) {

        if (count($args) != 2) {
            echo "Неверное количество параметров".PHP_EOL;
            return -1;
        }

        $oldPassword = $args[0];
        $newPassword = $args[1];

        $username = 'admin';

        $model = User::model()->find('LOWER(username)=?',array($username));

        if (!$model) {
            echo "Не найден аккаунт администратора".PHP_EOL;
            return -1;
        }


        if (!$model->validatePassword($oldPassword)) {
            echo "Неверный старый пароль".PHP_EOL;
            return -1;
        }

        $model->password = $model->hashPassword($newPassword);

        if ($model->save()) {
            echo "Пароль успешно изменен".PHP_EOL;
            return 0;
        }
        else {
            echo "Не удалось сохранить пароль".PHP_EOL;
            return -1;
        }

    }


    /*
     * Заполнение таблицы terms данными всех статей
     * Первоначально -- полная очистка таблицы terms
     * Вызов: php yiic database terms
     */

    public function ActionTerms() {

        try {
        // Очистка таблицы terms
        Terms::model()->deleteAll();

        // Массив всех статей:
        $articles = Articles::model()->findAll();

        // Заполнение таблицы Terms
        foreach($articles as $article) {
            $article->fillTerms();
        }

        echo "Заполнение закончено".PHP_EOL;
        return 0;
        }
        catch (CException $e) {
            echo "Ошибка заполнения".PHP_EOL;
            return -1;
        }

    }

    /*
     * Первоначальное заполнение индекса поиска Zend Lucene Search
     * файлы индекса - protected/runtime/search
     * Вызов: php yiic database searchIndex
     */
    public function ActionSearchIndex() {

      try {
        $lucene = new Lucene();
        $lucene->Create();
        echo "Индекс построен".PHP_EOL;
        return 0;
      }
      catch (CException $e) {
          echo "Ошибка построения индекса".PHP_EOL;
          return -1;
      }

    }
}



?>