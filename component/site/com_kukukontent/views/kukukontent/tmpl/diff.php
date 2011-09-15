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
<h1>Diff</h1>
<h2><?php echo $this->p; ?></h2>
<table class="diff" style="width: 100%; border: 1px solid gray; background-color: #fff; font-size: 10px;">
<tr>
	<th colspan="2" width="50%" style="background-color: #dfd;">
	    <?php echo $this->versionOne->modified; ?>
	</th>
	<th colspan="2" width="50%" style="background-color: #ffc;">
	    <?php echo $this->versionTwo->modified; ?>
	</th>
	</tr>
	<?php echo $this->diff; ?>
</table>

