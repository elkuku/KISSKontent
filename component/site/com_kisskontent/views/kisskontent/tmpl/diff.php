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

<div class="kissKontent<?php echo $this->pageclass_sfx;?>" id="KISSKontent">

<?php echo $this->menu(); ?>

<h1><?php echo jgettext('Differences');?></h1>
<?php
echo($this->diffAll)
? JHtml::link(JRoute::_('&diffAll=0'), jgettext('Show diferences only'))
: JHtml::link(JRoute::_('&diffAll=1'), jgettext('Show all'));
?>

<h2><?php echo $this->p; ?></h2>

<div id="kissDiffContainer">
    <?php echo $this->loadTemplate('diff'); ?>
</div>

</div><!-- kissKontent -->

<?php echo KISSKontentHelper::footer();

// var_dump($this->preview);
