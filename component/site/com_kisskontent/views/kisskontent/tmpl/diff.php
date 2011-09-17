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

echo $this->menu();

?>
<h1><?php echo jgettext('Differences');?></h1>

<h2><?php echo $this->p; ?></h2>

<table class="diff">
    <tr valign="top">
    	<th colspan="2" width="50%" style="background-color: #dfd; text-align: center;">
    	    <div style="text-align: left;">
    	        <?php echo $this->previous->link; ?>
    	    </div>
    	    <?php echo $this->versionOne->modified.' (#'.$this->versionOne->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionOne->name; ?>
    	    <br />
    		<em><?php echo $this->versionOne->summary; ?> </em>
    	</th>
    	<th colspan="2" width="50%" style="background-color: #ffc; text-align: center;">
    	    <div style="text-align: right;">
    	        <?php echo $this->next->link; ?>
    	   </div>
    	    <?php echo $this->versionTwo->modified.' (#'.$this->versionTwo->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionTwo->name; ?>
    	    <br />
    		<em><?php echo $this->versionTwo->summary; ?> </em>
    	</th>
	</tr>

	<?php echo $this->diff; ?>

</table>

<br />
<hr />

<h1>
<?php
//-- TRANSLATORS: A date and user name
$t =($this->next->link) ? jgettext('Version as of %s by %s') : jgettext('Actual version as of %s by %s');
echo sprintf($t, $this->preview->modified, $this->preview->name); ?>
</h1>

<hr />

<?php
echo $this->preview->text;

// var_dump($this->preview);
