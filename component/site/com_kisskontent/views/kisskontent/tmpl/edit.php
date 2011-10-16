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

<h1><?php echo ($this->content->text) ? jgettext('Edit') : jgettext('New'); ?></h1>

<?php echo $this->content->path; ?>

<h2>
    <?php echo KISSKontentHelper::drawFlag($this->content->lang); ?>
    <?php echo $this->content->page; ?>
</h2>

<?php
//-- ACL check
if($this->content->text)
{
    if( ! $this->canDo->get('core.edit'))
    {
        echo '<p>'.jgettext('You are not allowed to edit Kontent pages.').'</p></div>';

        return;
    }
}
else
{
    if( ! $this->canDo->get('core.create'))
    {
        echo '<p>'.jgettext('This page does not exist (yet), and you are not allowed to create pages.').'</p></div>';

        return;
    }
}
?>

<form method="post">

    <textarea id="kisskontentKontent" name="content"
    style="width: 100%; height: 300px;"><?php echo $this->content->text; ?></textarea>

    <p>
        <?php echo jgettext('Summary:'); ?> <input type="text" style="width: 80%" name="summary" />
    </p>

    <div id="kissEditButtons">
        <ul class="right">
            <li>
                <input type="submit" value="<?php echo jgettext('Save'); ?>" />
            </li>

            <?php if($this->content->text) : ?>
            <li>
                <input type="button" value="<?php echo jgettext('Cancel'); ?>"
                onClick="document.location.href='<?php echo JURI::current(); ?>';">
            </li>
            <?php endif; ?>
        </ul>

        <ul>
            <li>
                <input type="button" value="<?php echo jgettext('Preview'); ?>"
                onclick="kisskontentPreview('<?php echo JURI::root(); ?>', '<?php echo JRequest::getString('p'); ?>');" />
            </li>

            <li>
                <input type="button" value="<?php echo jgettext('Differences'); ?>"
                onclick="kisskontentDifferences('<?php echo JURI::root(); ?>', '<?php echo JRequest::getString('p'); ?>');" />
                <br />
                <input type="checkbox" id="chkDiffAll" />&nbsp;<label for="chkDiffAll"><?php echo jgettext('Complete'); ?></label>
            </li>
        </ul>

        <ul class="center">
            <li>
                <input type="button" value="<?php echo jgettext('Help !'); ?>" onclick="kissHelp('kisskontentPreview');" />
            </li>
        </ul>

    </div>

    <div class="clr"></div>

    <input type="hidden" name="kissLang" value="<?php echo $this->content->lang; ?>" />
    <input type="hidden" name="transTitle" value="<?php echo $this->content->title; ?>" />
    <input type="hidden" name="id_kiss" value="<?php echo $this->content->id_kiss; ?>" />

    <input type="hidden" name="p" value="<?php echo $this->content->title; ?>" />

    <input type="hidden" name="id" value="<?php echo $this->content->id; ?>" />
    <input type="hidden" name="task" value="save" />

    <?php echo JHtml::_('form.token'); ?>

</form>

<div id="kisskontentPreview"></div>

<?php //var_dump($this->content); ?>
</div>
<?php echo KISSKontentHelper::footer(); ?>
