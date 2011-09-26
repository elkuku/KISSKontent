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

$leftAdd = array();
$leftAdd[] = KISSKontentHelper::drawFlag($this->content->lang);

foreach ($this->translations as $tag => $p)
{
//     if(is_int($tag))
//     {
//         $langTag = $translation;
//         $p = 'Default';
//     }
//     else
//     {
//         $langTag = $tag;
//         $p = $translation;
//     }

    $class=($tag == $this->content->lang) ? 'class="active"' : '';

    $leftAdd[] = JHtml::link(JRoute::_('&lang=&p='.$p), $tag, $class);
}

$leftAdd = implode("\n", $leftAdd);

?>

<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

    <?php echo $this->menu($leftAdd); ?>

    <?php echo $this->content->text; ?>

</div>

<?php echo KISSKontentHelper::footer();
