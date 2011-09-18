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
<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

	<h1><?php echo $this->title; ?></h1>

<?php
if( ! $this->list) :
    echo '<h2>'.jgettext('No items found').'</h2>';

    echo '</div>';

    return;
endif;
?>

    <ul>
    <?php foreach ($this->list as $item) : ?>
        <li><?php echo $item; ?></li>
    <?php endforeach; ?>
    </ul>

</div>
