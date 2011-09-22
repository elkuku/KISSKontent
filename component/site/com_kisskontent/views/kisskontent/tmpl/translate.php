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

<noscript><?php echo jgettext('You should enable JavaScript'); ?></noscript>

<?php echo $this->menu(); ?>

<form method="post">

    <h1><?php echo jgettext('Translate');?></h1>

    <?php echo KISSKontentHelper::drawFlag($this->content->lang); ?>
    <select name="origLang">
        <?php echo $this->lists['origLang']; ?>
    </select>
    <br />
    <?php echo $this->content->path; ?>
    <h2><?php echo $this->content->titleName; ?></h2>

    <input id="origTitle" type="hidden" value="<?php echo $this->content->titleName; ?>" />


    <!-- Unescaped -->
    <div id="origText" style="display: none"><?php echo $this->content->text; ?></div>

    <!-- Escaped -->
    <div style="border: 1px solid silver; background-color: #eee; height: 150px; overflow: auto;">
        <?php echo nl2br(htmlspecialchars($this->content->text)); ?>
    </div>

    <hr />

    <?php echo KISSKontentHelper::drawFlag($this->translation->lang); ?>
        <?php
        /*
    <select name="lang">
        echo $this->lists['transLang'];
    </select>
    */
        ?>
    <br />

    <?php echo $this->translation->path; ?>
    <input type="hidden" name="transPath" value="<?php echo $this->translation->path; ?>" />

	<?php if($this->translation->title) : ?>
    	<h2><?php echo $this->translation->title; ?></h2>
    	<input name="transTitle" type="hidden" value="<?php echo $this->translation->title; ?>" />
    <?php else :?>
    	<br />
    	<input name="transTitle" id="transTitle" type="text" style="font-size: 1.3em;"
    	value="<?php echo $this->translation->title; ?>" />
    <?php endif; ?>

    <?php if( ! $this->translation->id) : ?>
        <input type="button" onclick="document.id('transTitle').value = document.id('origTitle').value;"
        value="<?php echo jgettext('Copy title'); ?>" />
    <?php endif; ?>

    <input type="button" onclick="document.id('transText').value = document.id('origText').innerHTML;"
    value="<?php echo jgettext('Copy text'); ?>" />
    <br />

	<textarea name="text" id="transText" rows="10"
	style="width: 100%"><?php echo $this->translation->text; ?></textarea>

    <p>
        <?php echo jgettext('Summary:'); ?> <input type="text" style="width: 80%" name="summary" />
    </p>

    <input type="submit" value="<?php echo jgettext('Translate'); ?>" />

    <input type="hidden" name="id" value="<?php echo $this->translation->id; ?>" />
    <input type="hidden" name="id_kiss" value="<?php echo $this->content->id; ?>" />
    <input type="hidden" name="lang" value="<?php echo $this->translation->lang; ?>" />
    <input type="hidden" name="task" value="dotranslate" />

    <?php echo JHtml::_('form.token'); ?>

</form>

</div>

<?php echo KISSKontentHelper::footer();
