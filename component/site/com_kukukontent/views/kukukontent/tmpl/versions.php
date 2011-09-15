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
	<?php if($i) : ?>
	 <a href="<?php echo jroute::_(JURI::current().'?task=diff&v1=HEAD&v2='.$version->id); ?>">diff</a>
	 <?php endif; ?>
	<?php echo $version->modified; ?>
	&nbsp;<?php echo $version->name; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php
// var_dump($this->versions);