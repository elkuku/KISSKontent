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

<?php echo $this->menu(); ?>

<h1><?php echo jgettext('Version history'); ?></h1>

<h2><?php echo ($this->p) ?: 'Default'; ?></h2>

    <fieldset>
        <legend><?php echo jgettext('Display options'); ?></legend>
        <?php //echo sprintf(jgettext('Display: %s'), implode(' | ', $this->alphaLinks)); ?>
        <?php echo KISSKontentHelper::drawLangChooser(); ?>
    </fieldset>

<?php if( ! $this->versions) :
    echo jgettext('No versions found.');
else : ?>


<ul>
    <?php foreach($this->versions as $i => $version) : ?>
    <li>

    <?php if(count($this->versions) > 1) : ?>
        <?php echo ($i)
        ? JHtml::link(KISSKontentHelper::getDiffLink($this->p, $version->id, 0), jgettext('Current'))
        : jgettext('Current'); ?>

        <?php echo (isset($this->versions[$i + 1]))
        ? JHtml::link(KISSKontentHelper::getDiffLink($this->p, $this->versions[$i + 1]->id, $version->id), jgettext('Previous'))
        : jgettext('Previous'); ?>
    <?php endif; ?>

    <?php echo $version->modified; ?>
    &nbsp;<?php echo $version->name; ?>
    &nbsp;(<?php echo sprintf(jgettext('%s Bytes'), KISSKontentHelper::strBytes($version->text)); ?>)

    <?php echo (KISS_ML) ? '&nbsp;'.KISSKontentHelper::drawFlag($version->lang) : ''; ?>
    <?php if($version->summary)
    echo '&nbsp;(<em>'.$version->summary.'</em>)';
//     var_dump($version);
    ?>

    </li>
    <?php endforeach; ?>
</ul>

<?php endif; ?>
</div>

<?php

echo KISSKontentHelper::footer();

//  var_dump($this->versions);
