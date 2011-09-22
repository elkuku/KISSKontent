<?php
/**
* @package    KISSKontent
* @subpackage Views
* @author     Nikolai Plath {@link http://nik-it.de}
* @author     Created on 09-Sep-2011
* @license    GNU/GPL
*/

//-- No direct access
defined('_JEXEC') || die('=;)');

?>
<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

	<h1><?php echo $this->title; ?></h1>

<?php if( ! $this->list) : ?>
    <h2><?php echo jgettext('No items found'); ?></h2>
<?php else : ?>

    <ul>
    <?php foreach ($this->list as $item) : ?>
        <li><?php
        echo $item->indentString;
        echo JHtml::link($item->href, $item->title, array('class' => $item->class));

        foreach ($item->translations as $tag) :
            echo '&nbsp;'.KISSKontentHelper::drawFlag($tag);
        endforeach;

        if($item->nukeHref) :
            echo '&nbsp; &rArr;';
            echo JHtml::link($item->nukeHref, jgettext('Nuke'), array('style' => 'font-weight: bold; color: red'));
        endif;
//         var_dump($item);
//         echo $item->level;
        ?></li>
    <?php endforeach; ?>
    </ul>

</div>

<?php endif;

echo KISSKontentHelper::footer();
