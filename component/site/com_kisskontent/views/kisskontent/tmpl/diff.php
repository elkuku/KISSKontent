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

//-- TRANSLATORS: A date and user name
$previewTitle =($this->next->link) ? jgettext('Version as of %s by %s') : jgettext('Actual version as of %s by %s');
?>

<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

<?php echo $this->menu(); ?>

<h1><?php echo jgettext('Differences');?></h1>
<?php
echo($this->diffAll)
? JHtml::link(JRoute::_('&diffAll=0'), jgettext('Show diferences only'))
: JHtml::link(JRoute::_('&diffAll=1'), jgettext('Show all'));
?>

<h2><?php echo $this->p; ?></h2>

<table class="diff">
    <tr>
    	<th colspan="2" class="diffLeft">
    	    <?php echo $this->versionOne->modified.' (#'.$this->versionOne->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionOne->name; ?>
    	    <br />
    		<em><?php echo $this->versionOne->summary; ?> </em>
    	    <?php echo $this->previous->link; ?>
    	</th>

    	<th colspan="2" class="diffRight">
    	    <?php echo $this->versionTwo->modified.' (#'.$this->versionTwo->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionTwo->name; ?>
    	    <br />
    		<em><?php echo $this->versionTwo->summary; ?> </em>
  	        <?php echo $this->next->link; ?>
    	</th>
	</tr>

	<?php echo $this->diff; ?>

</table>

<br />

<hr />

<h1><?php echo sprintf($previewTitle, $this->preview->modified, $this->preview->name); ?></h1>

<hr />

<?php echo $this->preview->text; ?>

</div>

<?php echo KISSKontentHelper::footer();

// var_dump($this->preview);
