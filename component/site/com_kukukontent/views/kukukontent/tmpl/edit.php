<?php
// var_dump($this->content);
?>
<h1><?php echo($this->content->text) ? jgettext('Edit') : jgettext('New'); ?></h1>
<h2><?php echo $this->content->path; ?></h2>

<form method="post">

<input type="submit" value="<?php echo jgettext('Save'); ?>" />
<a href="<?php echo JURI::current(); ?>"><?php echo jgettext('Cancel'); ?></a>

<textarea name="content" style="width: 100%; height: 400px;"><?php echo $this->content->text; ?></textarea>

<input type="hidden" name="id" value="<?php echo $this->content->id; ?>" />
<input type="hidden" name="task" value="save" />
<?php echo JHtml::_('form.token'); ?>

</form>