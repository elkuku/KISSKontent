<?php
echo 'edit';

?>
<h1><?php echo $this->content->path; ?></h1>
<form>
<textarea style="width: 100%; height: 200px;"><?php echo $this->content->text; ?></textarea>
</form>