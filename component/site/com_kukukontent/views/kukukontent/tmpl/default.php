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

?>

<div class="kukuKontent">
    <?php echo $this->menu(); ?>

    <?php echo $this->content->text; ?>

</div>
