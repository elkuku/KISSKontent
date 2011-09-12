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
class KuKuKontentViewKuKuKontent extends JView
{
    protected $content = '';

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
        JHtml::_('script', 'com_kukukontent/kukukontent.js', array(), true);

        $this->content = $this->get('content');

        $task = JRequest::getCmd('task');

        if( ! $this->content->text
        || 'edit' == $task)
        {
            $this->setLayout('edit');
        }
        else
        {
            JPluginHelper::importPlugin('content');

            $content = JDispatcher::getInstance()->trigger('onContentPrepare'
            , array('text', &$this->content, &$this->params));
        }

        $this->setPathway();

        parent::display($tpl);

        return;
    }//function

    /**
     * Set the pathway.
     *
     * @return void
     */
    protected function setPathway()
    {
        if( ! $this->content->path)
        return;

        $pathway = JFactory::getApplication()->getPathway();

        $items = $pathway->getPathway();

        $parts = explode('/', $this->content->path);

        $combined = '';

        $baseLink = $items[0]->link;

        foreach($parts as $part)
        {
            if( ! $part
            || 'Default' == $part)
            continue;

            $combined .=($combined) ? '/'.$part : $part;

            $p = new stdClass;

            $p->name = $part;
            $p->link = JRoute::_($baseLink.'&p='.$combined);

            $items[] = $p;
        }//foreach

        $pathway->setPathway($items);

        return;
    }//function
}//class
