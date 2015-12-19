<?php /* @var Controller $this */ ?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <link rel="canonical" href="<?php echo Yii::app()->getBaseUrl(true) . '/' . Yii::app()->request->getPathInfo(); ?>" />

    <?php if ($this->description): ?>
        <meta name="description" content="<?php echo strip_tags($this->description); ?>" />
    <?php endif ?>

    <?php if ($this->keywords): ?>
        <meta name="keywords" content="<?php echo strip_tags($this->keywords); ?>" />
    <?php endif ?>

    <title>
        <?php
        $title = $this->pageTitle ? ' - '.$this->pageTitle : '';
        echo strip_tags(Yii::app()->name).$title;
        ?>
    </title>


</head>

<body>

<header>
<div id="banner"></div>
<?php
// Верхнее горизонтальное меню
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brandLabel' => 'Главная', //
    'brandOptions'=>array('rel'=>'nofollow',),
    'display' => null, // default is static to top
    'collapse' => true, // responsive

    'items' => array(

        array(
            'class' => 'bootstrap.widgets.TbNav',
            'items' => $this->menu,
            ),
        array(
            'class'=> 'bootstrap.widgets.TbNav',
            'items'=> array(
                array(
                'label'=> Yii::app()->user->isGuest ? 'Вход' : Yii::app()->user->name.' - Выход',
                'url' => Yii::app()->user->isGuest ?
                        $this->createUrl('#') : $this->createUrl('site/logout'),
               'linkOptions' => Yii::app()->user->isGuest ?
                       array('data-toggle'=>'modal',
                           'data-target'=>'#loginModalForm','rel'=>'nofollow',)
                       : array('rel'=>'nofollow',),
                ),
            ),
            'htmlOptions' => array('class'=>'pull-right'),
            )
    ),
)); ?>

</header>

<?php
// Форма входа пользователя
if (Yii::app()->user->isGuest) {

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'loginModalForm',
    'header' => 'Вход пользователя',
    'content' => $this->renderPartial('//site/login',array(),true),
    'footer' => array(
        TbHtml::button('Войти',
            array( 'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'id'=>'saveModalForm',
                'onclick'=>'$("#loginForm").submit()')),
        TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
    ),
));

}
?>


<div id="main" class="container">

    <div class="row" id="mainRow">

        <div class="span2" id="adminSidebar">
            <?php if(Yii::app()->user->isGuest ||
                ($this->id == 'site' && $this->action->id == 'index')) {
                $this->widget('FindForm');
                $this->widget('Contacts');
            }

            ?>
            <?php if(!Yii::app()->user->isGuest)
                $this->widget('AdminMenu',array('menu'=>$this->adminMenu,)); ?>
        </div>

        <div class="span10" id="contentSidebar">
            <?php

            echo $content;

            ?>
        </div>

    </div> <!--     row -->

    <!-- для перекрытия футера - см. http://getbootstrap.com/2.3.2/examples/sticky-footer.html -->
    <div id="push"></div>

</div> <!-- main -->


<footer class="visible-desktop"> <!-- на мобильных устр-вах футера нет -->
    <div class="container">
        <div class="row">
            <div class="span6">
                &copy; Научно-образовательная лаборатория региональных филологических исследований, 2014-2016
            </div>
            <div class="span2">
                Автор словаря <a href="mailto:bolshakova55@yandex.ru">Н.В. Большакова</a>
            </div>
            <div class="span2">
                Программирование <a href="mailto:vkabachenko@gmail.com">В.В. Кабаченко</a>
            </div>
            <div class="span2">
                Контент-менеджер <a href="mailto:prosergik@gmail.com">С. Веселов</a>
            </div>

        </div>
    </div>

</footer>


</body>
</html>

