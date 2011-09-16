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

echo '<h1>'.jgettext('Version history').'</h1>';
echo '<h2>'.$this->p.'</h2>';

if( ! $this->versions) :
    echo jgettext('No versions found.');

    return;
endif;
?>

<ul>
<?php foreach ($this->versions as $i => $version) : ?>
	<li>
	<?php echo ($i)
	? JHtml::link(JURI::current().'?task=diff&v1='.$version->id.'&v2=0', jgettext('Current'))
	: jgettext('Current'); ?>

	<?php echo (isset($this->versions[$i + 1]))
	? JHtml::link(JURI::current().'?task=diff&v1='.$this->versions[$i + 1]->id.'&v2='.$version->id, jgettext('Previous'))
	: jgettext('Previous'); ?>
	<?php if(isset($this->versions[$i + 1])) : ?>
	 <?php endif; ?>
	<?php echo $version->modified; ?>
	&nbsp;<?php echo $version->name; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php
// var_dump($this->versions);