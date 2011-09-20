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
$previewTitle =($this->diff->next->link) ? jgettext('Version as of %s by %s') : jgettext('Actual version as of %s by %s');
?>

<table class="diff">
    <tr>
    	<th colspan="2" class="diffLeft">
    	    <?php echo $this->diff->versionOne->modified.' (#'.$this->diff->versionOne->id.')'; ?>
    	    <br />
    	    <?php echo $this->diff->versionOne->name; ?>
    	    <br />
    		<em><?php echo $this->diff->versionOne->summary; ?> </em>
    	</th>

    	<th colspan="2" class="diffRight">
    	    <?php echo $this->diff->versionTwo->modified.' (#'.$this->diff->versionTwo->id.')'; ?>
    	    <br />
    	    <?php echo $this->diff->versionTwo->name; ?>
    	    <br />
    		<em><?php echo $this->diff->versionTwo->summary; ?> </em>
    	</th>
    </tr>

    <tr>
    	<th colspan="2"><?php echo $this->diff->previous->link; ?></th>
    	<th colspan="2"><?php echo $this->diff->next->link; ?></th>
	</tr>

	<?php echo $this->diff->diff; ?>

</table>

<br />

<hr />

<h1><?php echo sprintf($previewTitle, $this->diff->preview->modified, $this->diff->preview->name); ?></h1>

<hr />

<?php echo $this->diff->preview->text; ?>
