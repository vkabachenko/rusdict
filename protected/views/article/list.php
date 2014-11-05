<?php

/* @var $navList array */

    if (!count($navList)):
?>
<h2>Статей не найдено</h2>

<?php
    else:

        echo TbHtml::navList($navList);

    endif;
?>


