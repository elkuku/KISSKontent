<?php
/**
 * @package    KuKuKontent
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
    <tr>
    	<th colspan="2" width="50%" style="background-color: #dfd; text-align: center;">
    	    <?php echo $this->versionOne->modified.' (#'.$this->versionOne->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionOne->name; ?>
    	    <div style="text-align: left;">
    	        <?php echo $this->previous->link; ?>
    	    </div>
    	</th>
    	<th colspan="2" width="50%" style="background-color: #ffc; text-align: center;">
    	    <?php echo $this->versionTwo->modified.' (#'.$this->versionTwo->id.')'; ?>
    	    <br />
    	    <?php echo $this->versionTwo->name; ?>
    	    <div style="text-align: right;">
    	        <?php echo $this->next->link; ?>
    	   </div>
    	</th>
	</tr>

	<?php echo $this->diff; ?>

</table>
<?php
// var_dump($this->versionOne);