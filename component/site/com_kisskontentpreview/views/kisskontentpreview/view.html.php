<?php
/**
 * @package    KISSKontentPreview
 * @subpackage Views
 * @author      {@link }
 * @author     Created on 09-Dec-2012
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


/**
 * HTML View class for the KISSKontentPreview Component.
 *
 * @package KISSKontentPreview
 */
class KISSKontentPreviewViewKISSKontentPreview extends JViewLegacy
{
	/**
	 * Output raw markdown and echo it out.
	 *
	 * @param   string   $raw      The raw string.
	 * @param   boolean  $comment  If TRUE, the raw text will be prepended for documentation.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	protected function parse($raw, $comment = true)
	{
		$o       = new stdClass;
		$o->text = $raw;

		$html = array();

		$html[] = '<div class="row-fluid">';

		if ($comment)
		{
			$html[] = '<div class="span6">';
			$html[] = '<h3>Code</h3>';
			$html[] = '</div>';
			$html[] = '<div class="span6">';
			$html[] = '<h3>Output</h3>';
			$html[] = '</div>';
			$html[] = '</div>';

			$html[] = '<div class="row-fluid">';
			$html[] = '<div class="span6">';
			$html[] = '<pre>' . $o->text . '</pre>';
			$html[] = '</div>';
			$html[] = '<div class="span6">';
		}
		else
		{
			$html[] = '<div class="span12">';
		}

		$params = new JRegistry;

		JEventDispatcher::getInstance()->trigger('onContentPrepare', array('com_content.article', &$o, &$params));

		$html[] = $o->text;
		$html[] = '</div>';

		$html[] = '</div>';

		return implode("\n", $html);
	}

}
