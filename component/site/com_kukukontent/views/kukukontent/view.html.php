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
        $tag = JFactory::getLanguage()->getTag();

        if(JFile::exists(JPATH_COMPONENT_SITE.'/demo/'.$tag.'.md'))
        {
            $content = JFile::read(JPATH_COMPONENT_SITE.'/demo/'.$tag.'.md');
        }
        else
        {
            $content = JFile::read(JPATH_COMPONENT_SITE.'/demo/en-GB.md');
        }

        JPluginHelper::importPlugin('content');

        $o = new stdClass;
        $o->text = $content;

        $content = JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$o, &$this->params));

        $this->content = $o->text;

        parent::display($tpl);
    }//function
}//class
