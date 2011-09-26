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

$actDate = '';
$actTitle = '';
?>
<div class="kissKontent<?php echo $this->pageclass_sfx;?>">
	<h1><?php echo jgettext('Recent changes'); ?></h1>

    <fieldset>
    <legend><?php echo jgettext('Display options'); ?></legend>

<?php
// TRANSLATORS: Numbers
echo sprintf(jgettext('Show the latest %1s | %2s | %3s | %4s | %5s changes.')
, $this->getLink('10')
, $this->getLink('50')
, $this->getLink('100')
, $this->getLink('250')
, $this->getLink('500')
);

echo (KISS_ML) ? '<p>'.KISSKontentHelper::drawLangChooser().'</p>' : '';
?>

	</fieldset>

<?php
if( ! $this->list) :
    echo '<h2>'.jgettext('Nothing has changed').'</h2>';
    echo '</div>'.NL;

    return;
endif;
?>

<?php foreach($this->list as $item) :
    list($date, $time) = explode(' ', $item->modified);

    if( ! $actDate || $actDate != $date)
    {
        echo ($actDate) ? '</ul>' : '';//-- Close previous

        echo '<h2>'.$date.'</h2>'.NL;//@todo format
        echo '<ul>';

        $actDate = $date;
    }

    echo '<li>';

    echo $time;

    echo ( ! $item->diffLink) ? '&nbsp;<b>N</b>&nbsp;' : '&nbsp;';

    if(1)
    {
        echo ($item->lang) ? KISSKontentHelper::drawFlag($item->lang).'&nbsp;' : '';
    }

    if( ! $actTitle || $actTitle != $item->title)
    {
        echo JHtml::link($item->link, $item->title);

        if($item->diffLink) :
            echo '&nbsp;(';
            echo JHtml::link($item->diffLink, jgettext('Differences'));
            echo '&nbsp;|&nbsp;';
            echo JHtml::link($item->versionsLink, jgettext('Versions'));
            echo ')';
        endif;

        $actTitle = $item->title;
    }
    else
    {
//         echo str_repeat('.', strlen($actTitle)).' ';
        echo ' .... ';

        if($item->diffLink) :
            echo JHtml::link($item->diffLink, jgettext('Differences'));
        else :
            echo JHtml::link($item->link, $item->title);
        endif;
    }

    echo ' .. '.$item->name;

    if($item->summary)
    echo '&nbsp;(<em>'.$item->summary.'</em>)';

    echo '</li>'.NL;
endforeach;

echo '</ul>'.NL;

// var_dump($this->list);
?>
</div>

<?php echo KISSKontentHelper::footer();
