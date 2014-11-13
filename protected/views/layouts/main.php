<?php /* @var $this Controller */ ?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>

</head>

<body>

<header>
<div id="banner"></div>
<?php
// Верхнее горизонтальное меню
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brandLabel' => 'Главная', //
    'display' => null, // default is static to top
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
                       array('data-toggle'=>'modal','data-target'=>'#loginModalForm') : array(),
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
                'id'=>'saveModalForm',)),
        TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
    ),
));

Yii::app()->clientScript->registerScript('btnSaveModalForm', "
    jQuery('#saveModalForm').click(function(){
        jQuery('#loginForm').submit();
    });", CClientScript::POS_READY);

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
            <?php echo $content; ?>
        </div>

    </div> <!--     row -->

    <!-- для перекрытия футера - см. http://getbootstrap.com/2.3.2/examples/sticky-footer.html -->
    <div id="push"></div>

</div> <!-- main -->


<footer>
    <div class="container">
        <div class="row">
            <div class="span8">
                <p>&copy; Научно-образовательная лаборатория региональных филологических исследований</p>
            </div>
            <div class="span4">
                <a href="mailto:labrfi@yandex.ru" class="pull-right" id="email">labrfi@yandex.ru</a>
            </div>
        </div>
    </div>

</footer>


</body>
</html>

