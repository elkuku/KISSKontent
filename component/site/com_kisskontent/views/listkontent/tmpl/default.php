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

if( ! $this->list) :
    echo jgettext('No items found');

    return;
endif;
?>

<ul>
<?php foreach ($this->list as $item) : ?>
    <li><?php echo $item; ?></li>
<?php endforeach; ?>
</ul>