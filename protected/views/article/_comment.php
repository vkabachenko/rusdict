<?php
/* @var $data Comments */
?>
<p class="author">
    <?php
        echo "$data->username, ";
        echo date("d-m-Y",strtotime($data->date_created));
    ?>
</p>
<p class="comment">
    <?php
        echo strip_tags($data->comment);
    ?>
</p>