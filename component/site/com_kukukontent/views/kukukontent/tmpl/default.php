<?php
/**
 * @version SVN: $Id: builder.php 469 2011-07-29 19:03:30Z elkuku $
 * @package    KuKuKontent
 * @subpackage Views
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

JHtml::_('stylesheet', 'com_kukukontent/kukukontent.css', array(), true);

?>
<a href="<?php echo JURI::current().'?task=edit'; ?>">Edit</a>
<?php
echo $this->content->text;

// var_dump($this->content);