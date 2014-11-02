<?php



class DatabaseCommand extends CConsoleCommand {
/* @var $model User */

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





}



?>