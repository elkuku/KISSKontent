<?php

?>
<h1><?php echo jgettext('Recent changes'); ?></h1>

<fieldset>
<legend><?php echo jgettext('Display options'); ?></legend>
<?php echo sprintf(jgettext('Show the latest %s | %s | %s | %s | %s changes.')
, $this->getLink('10')
, $this->getLink('50')
, $this->getLink('100')
, $this->getLink('250')
, $this->getLink('500')
);?>
</fieldset>

<ul>
<?php foreach ($this->list as $item) :
    echo '<li>';
    echo $item->modified;

    echo ($item->diffLink) ? '&nbsp;&bull;&nbsp;' : '&nbsp;<b>N</b>&nbsp;';

    echo JHtml::link($item->link, $item->title);

    if($item->diffLink) :
        echo '&nbsp;(';
        echo JHtml::link($item->diffLink, jgettext('Difference'));
        echo '&nbsp;|&nbsp;';
        echo JHtml::link($item->versionsLink, jgettext('Versions'));
        echo ')';
    endif;

    echo ' .. '.$item->name;
    echo '</li>';
endforeach;?>
</ul>
<?php
// var_dump($this->list);