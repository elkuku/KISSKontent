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

    <fieldset>
	    <legend><?php echo jgettext('Display options'); ?></legend>
        <?php
        // TRANSLATORS: A list of letters
        echo sprintf(jgettext('Display: %s'), implode(' | ', $this->alphaLinks)); ?>
        <p><?php echo (KISS_ML) ? KISSKontentHelper::drawLangChooser() : ''; ?></p>
    </fieldset>

    <?php if( ! $this->list) : ?>
    	<h2><?php echo jgettext('No items found'); ?></h2>
    <?php else : ?>

    <ul>
    <?php foreach ($this->list as $item) : ?>
        <li><?php
        echo $item->indentString;
        echo JHtml::link($item->href, $item->title, array('class' => $item->class));

        foreach ($item->translations as $tag => $title) :
            echo '&nbsp;'.KISSKontentHelper::drawFlag($tag);
            echo '&nbsp;'.JHtml::link(KISSKontentHelper::getLink($title), $tag, array('title' => $title));
        endforeach;

        if($item->nukeHref) :
            echo '&nbsp; &rArr;';
            // TRANSLATORS: The term Nuke refers to completely remove the Kontent item including versions and translations
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

// var_dump($this->list);
