<?php
/**
 * @package    KISSKontentPreview
 * @subpackage Base
 * @author      {@link }
 * @author     Created on 09-Dec-2012
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


/**
 * KISSKontentPreview Controller.
 *
 * @package    KISSKontentPreview
 * @subpackage Controllers
 */
class KISSKontentPreviewController extends JControllerLegacy
{
	public function preview()
	{
		$app = JFactory::getApplication();

		$o       = new stdClass;
		$o->text = $this->input->getHtml('text', '');

		if (!$o->text)
		{
			echo 'Nothing to preview...';
			$app->close();
		}

		$params = new JRegistry;
		$params->set('luminous.format', 'html-full');

		JPluginHelper::importPlugin('content');

		JEventDispatcher::getInstance()->trigger('onContentPrepare', array('com_content.article', &$o, &$params));

		echo $o->text ? : 'Nothing to preview...';

		$app->close();

	}
}
