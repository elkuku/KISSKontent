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

$leftAdd = '';
$leftAdd .= KISSKontentHelper::drawFlag($this->content->lang);
?>

<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

    <?php echo $this->menu($leftAdd); ?>

    <?php echo $this->content->text; ?>

</div>

<?php echo KISSKontentHelper::footer();
