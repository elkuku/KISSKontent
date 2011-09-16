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

//-- Import the JView class
jimport('joomla.application.component.view');

/**
 * HTML View class for the KuKuKontent Component.
 *
 * @package KuKuKontent
 */
class KuKuKontentViewRecentChanges extends JView
{
    protected $list = array();

    /**
     * KuKuKontent view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('stylesheet', 'com_kukukontent/kukukontent.css', array(), true);

        $this->list = $this->get('list');

        parent::display($tpl);
    }//function

    protected function getLink($num)
    {
        $attribs =($num == JRequest::getInt('limit', 10)) ? ' class="active"' : '';

        return JHtml::link(JURI::current().'?limit='.$num, $num, $attribs);
    }//function
}//class
