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

//-- Import the JView class
jimport('joomla.application.component.view');

/**
 * HTML View class for the KISSKontent Component.
 *
 * @package KISSKontent
 */
class KISSKontentViewRecentChanges extends JView
{
    protected $list = array();

    /**
     * KISSKontent view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('stylesheet', 'com_kisskontent/kisskontent.css', array(), true);

        $this->list = $this->get('list');

        parent::display($tpl);
    }//function

    protected function getLink($num)
    {
        $attribs =($num == JRequest::getInt('limit', 10)) ? ' class="active"' : '';

        return JHtml::link(JRoute::_('&limit='.$num), $num, $attribs);
    }//function
}//class
