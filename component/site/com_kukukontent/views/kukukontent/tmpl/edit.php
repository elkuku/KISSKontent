<?php
// var_dump($this->content);
?>
<h1><?php echo ($this->content->text) ? jgettext('Edit') : jgettext('New'); ?></h1>
<h2><?php echo $this->content->path; ?></h2>

<?php
//-- ACL check
if($this->content->text)
{
    if( ! $this->canDo->get('core.edit'))
    {
        echo '<p>'.jgettext('You are not allowed to edit pages.');

        return;
    }
}
else
{
    if( ! $this->canDo->get('core.create'))
    {
        echo '<p>'.jgettext('This page does not exist (yet), and you are not allowed to create pages.');

        return;
    }
}
?>

<div id="kukukontentPreview"></div>

<form method="post">

<input type="button" value="<?php echo jgettext('Preview'); ?>" onclick="kukukontentPreview('<?php echo JURI::root(); ?>', '<?php echo JRequest::getString('p'); ?>');" />
<input type="submit" value="<?php echo jgettext('Save'); ?>" />

<?php if($this->content->text) : ?>
<input type="button" value="<?php echo jgettext('Cancel'); ?>" onClick="document.location.href='<?php echo JURI::current(); ?>';">
<?php endif; ?>

<textarea id="kukukontentKontent" name="content"
style="width: 100%; height: 300px;"><?php echo $this->content->text; ?></textarea>

<input type="hidden" name="id" value="<?php echo $this->content->id; ?>" />
<input type="hidden" name="task" value="save" />
<?php echo JHtml::_('form.token'); ?>

</form>
