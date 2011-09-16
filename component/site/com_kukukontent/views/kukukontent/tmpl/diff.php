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
<h1><?php echo jgettext('Diff');?></h1>

<h2><?php echo $this->p; ?></h2>

<table class="diff" style="width: 100%; border: 1px solid gray; background-color: #fff; font-size: 10px;">
<tr>
	<th colspan="2" width="50%" style="background-color: #dfd;">
	    <?php echo $this->versionOne->modified; ?>
	    <br />
	    <?php echo $this->versionOne->name; ?>
	</th>
	<th colspan="2" width="50%" style="background-color: #ffc;">
	    <?php echo $this->versionTwo->modified; ?>
	    <br />
	    <?php echo $this->versionTwo->name; ?>
	</th>
	</tr>

	<?php echo $this->diff; ?>

</table>
<?php
// var_dump($this->versionOne);