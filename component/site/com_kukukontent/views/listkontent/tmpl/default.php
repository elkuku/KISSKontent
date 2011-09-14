<?php

if( ! $this->list) :
    echo jgettext('No items found');

    return;
endif;
?>
<ul>
<?php foreach ($this->list as $item) : ?>
    <li><?php echo $item; ?></li>
<?php endforeach; ?>
</ul>