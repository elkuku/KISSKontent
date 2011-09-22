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
class KISSKontentViewKISSKontent extends JView
{
    protected $content = '';

    protected $canDo = false;

    protected $p = '';

    /**
     * KISSKontent view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        $this->p = JRequest::getString('p');

        $task = JRequest::getCmd('task');

        if(in_array($task, get_class_methods($this)))
        {
            $this->$task();
        }
        else
        {
            if($task)
            echo sprintf('UNDEFINED Task %s in view %s', $task, $this->_name).'<br />';

            $this->defaultTask();
        }

        JHtml::_('stylesheet', 'com_kisskontent/kisskontent.css', array(), true);
        JHtml::_('script', 'com_kisskontent/kisskontent.js', array(), true);

        $appParams = JFactory::getApplication()->getParams();

        $this->pageclass_sfx = htmlspecialchars($appParams->get('pageclass_sfx'));

        $this->canDo = KISSKontentHelper::getActions();

        $this->setPathway();

        parent::display($tpl);

        return;
    }//function

    protected function save()
    {
        $this->defaultTask();
    }//function

    protected function read()
    {
        $this->defaultTask();
    }//function

    protected function defaultTask()
    {
        $this->content = $this->get('content');

        if( ! $this->content->text)
        {
            $this->setLayout('edit');

            return;
        }

        //-- Process internal links
        $this->content->text = KISSKontentHelper::preParse($this->content->text);

        JPluginHelper::importPlugin('content');

        JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$this->content, &$this->params));
    }//function

    protected function edit()
    {
        $this->content = $this->get('content');

        if( ! $this->content->id)
        {
            JRequest::setVar('task', '');
            $this->defaultTask();

            return;
        }

        JHtml::_('stylesheet', 'com_kisskontent/diff.css', array(), true);


        $this->setLayout('edit');
    }//function

    protected function translate()
    {
        $targetLang =(class_exists('g11n')) ? g11n::getDefault() : 'en-GB';

        $model = $this->getModel();

        $this->content = $model->getContent('default');
        //         $this->content = $this->get('content');

        if( ! $this->content->id)
        {
            JRequest::setVar('task', '');
            $this->defaultTask();

            return;
        }

        $parts = explode('/', $this->content->title);

        if(count($parts) > 1)
        {
            //             var_dump($parts);
        }


        $this->content->titleName = array_pop($parts);
        $this->content->path = implode('/', $parts);

        $this->translation = $this->get('translation');

        if('default' == strtolower($this->content->title))
        {
            $this->translation->title = 'Default';
        }

        $parts = explode('/', $this->translation->title);

        if(count($parts) > 1)
        {
            //             var_dump($parts);
        }


        $this->translation->titleName = array_pop($parts);
        $this->translation->path = implode('/', $parts);
        $this->translation->lang = $targetLang;


        $langs = JFactory::getLanguage()->getKnownLanguages();

        $options = array();
        $options['orig'] = array();
        $options['trans'] = array();

        foreach ($langs as $lang)
        {
            $options['orig'][] = JHtml::_('select.option', $lang['tag']);
            //            $options['trans'][] = JHtml::_('select.option', $lang['tag']);
        }//foreach

        array_unshift($options['orig'], JHtml::_('select.option', '', jgettext('Default')));

        $this->lists = array();


        $this->lists['origLang'] = JHtml::_('select.options', $options['orig']);

        //        $this->lists['transLang'] = JHtml::_('select.options', $options['trans'], 'value', 'text', $targetLang);

        $this->setLayout('translate');
    }//function

    protected function versions()
    {
        $this->versions = $this->get('versions');

        $this->setLayout('versions');
    }//function

    protected function diff()
    {
        JHtml::_('behavior.framework');
        JHtml::_('stylesheet', 'com_kisskontent/diff.css', array(), true);
        JHtml::_('script', 'com_kisskontent/diff.js', array(), true);

        $this->diffAll =(JRequest::getInt('diffAll')) ? true : false;

        $this->diff = KISSKontentHelper::getDiffFromRequest();

        $this->setLayout('diff');
    }//function

    /**
     * Set the pathway.
     *
     * @return void
     */
    protected function setPathway()
    {
        $title =(isset($this->content->title)) ? $this->content->title : $this->p;

        if( ! $title)//-- No path, no -way...
        return;

        $pathway = JFactory::getApplication()->getPathway();

        $items = $pathway->getPathway();

        if($items
        && ! $this->isDefaultView())
        array_pop($items);

        $parts = explode('/', $title);

        $combined = '';

        $baseLink =(isset($items[0]->link)) ? $items[0]->link : '';

        foreach($parts as $part)
        {
            if( ! $part
            || 'Default' == $part)
            continue;

            $combined .=($combined) ? '/'.$part : $part;

            $p = new stdClass;

            $p->name = $part;
            $p->link = JRoute::_($baseLink.'&task=read&p='.$combined);
            $p->class = KISSKontentHelper::isLink($combined) ? '' : 'internal redlink';

            $items[] = $p;
        }//foreach

        $pathway->setPathway($items);

        return;
    }//function

    protected function isDefaultView()
    {
        $menus = JFactory::getApplication()->getMenu('site');

        //-- Get default from active menu
        $active = $menus->getActive();

        $activeId =($active) ? $active->id : 1;

        if( ! $activeId)
        return false;

        $menus = JFactory::getApplication()->getMenu('site');

        $cId = JComponentHelper::getComponent('com_kisskontent')->id;

        $items = $menus->getItems('component_id', $cId);

        if($items)
        {
            foreach($items as $item)
            {
                if(isset($item->query['view'])
                && 'kisskontent' == $item->query['view'])
                {
                    $Itemid = $item->id;//-- HEUREKA =;)

                    break;
                }
            }//foreach
        }

        if( ! $Itemid)
        return false;

        return ($Itemid == $activeId);
    }

    /**
     * KISS menu =;)
     *
     * @return string
     */
    protected function menu($leftAdd = 'huhu')
    {
        $task = JRequest::getCmd('task');

        $html = array();

        $html[] = '<div id="kissActionMenu">';

        $html[] = '<div class="kissKredits">';
        $html[] = $leftAdd;
        $html[] = '</div>';

        $html[] = '<div class="kissActions">';

        $html[] = '   <ul>';

        $activeS =' class="active"';

        $active =('' == $task || in_array($task, array('read', 'save'))) ? $activeS : '';
        $html[] = '      <li>'.JHtml::link(JRoute::_('&task=read'), jgettext('Read'), $active).'</li>';

        $active =('edit' == $task) ? $activeS : '';
        if($this->canDo->get('core.edit'))
        $html[] = '      <li>'.JHtml::link(JRoute::_('&task=edit'), jgettext('Edit'), $active).'</li>';

        $active =('translate' == $task) ? $activeS : '';
        if($this->canDo->get('core.translate'))
        $html[] = '      <li>'.JHtml::link(JRoute::_('&task=translate'), jgettext('Translate'), $active).'</li>';

        $active =(in_array($task, array('versions', 'diff'))) ? $activeS : '';
        $html[] = '      <li>'.JHtml::link(JRoute::_('&task=versions'), jgettext('Version history'), $active).'</li>';

        $html[] = '   </ul>';

        $html[] = '</div>';
        $html[] = '<div class="clr"></div>';

        $html[] = '</div>';

        return implode("\n", $html);
    }//function
}//class
