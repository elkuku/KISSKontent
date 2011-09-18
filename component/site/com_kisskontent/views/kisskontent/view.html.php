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

        $content = JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$this->content, &$this->params));
    }//function

    protected function edit()
    {
        $this->content = $this->get('content');

        $this->setLayout('edit');
    }//function

    protected function versions()
    {
        $this->versions = $this->get('versions');

        $this->setLayout('versions');
    }//function

    protected function diff()
    {
        JHtml::_('stylesheet', 'com_kisskontent/diff.css', array(), true);

        $model = $this->getModel();

        $this->versionOne = $model->findVersion(JRequest::getInt('v1'));
        $this->versionTwo = $model->findVersion(JRequest::getInt('v2'));


        $this->previous = $model->getPrevious($this->versionOne->id);

        if($this->previous)
        {
            $url = KISSKontentHelper::getDiffLink($this->p, $this->previous->id, $this->versionOne->id);
            $this->previous->link = JHtml::link($url, jgettext('To previous version difference'));
        }
        else
        {
            $this->previous->link = '';
        }

        $this->next = $model->getNext($this->versionTwo->id);

        if($this->next)
        {
            $url = KISSKontentHelper::getDiffLink($this->p, $this->versionTwo->id, $this->next->id);
            $this->next->link = JHtml::link($url, jgettext('To next version difference'));
        }
        else
        {
            $this->next->link = '';
        }

        $this->diff = $this->getDiffTable($this->versionOne->text, $this->versionTwo->text);

        //-- Process internal links
        $this->preview = $this->versionTwo;

        $this->preview->text = KISSKontentHelper::preParse($this->preview->text);

        JPluginHelper::importPlugin('content');

        $content = JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$this->preview, &$this->params));

        $this->setLayout('diff');
    }//function

    /**
     * Set the pathway.
     *
     * @return void
     */
    protected function setPathway()
    {
        if( ! $this->p)//-- No path, no -way...
        return;

        $pathway = JFactory::getApplication()->getPathway();

        $items = $pathway->getPathway();

//         if( ! $items)
//         return;// No pathway :(

        $parts = explode('/', $this->p);

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
            $p->link = JRoute::_($baseLink.'&p='.$combined);

            $items[] = $p;
        }//foreach

        $pathway->setPathway($items);

        return;
    }//function

    /**
     * KISS menu =;)
     *
     * @return string
     */
    protected function menu()
    {
        $task = JRequest::getCmd('task');

        $html = '';

        $html .= '<div style="text-align: right">';

        $activeS =' class="active"';

        $active =('' == $task || in_array($task, array('read', 'save'))) ? $activeS : '';
        $html .= JHtml::link(JRoute::_('&task=read'), jgettext('Read'), $active);

        $active =('edit' == $task) ? $activeS : '';

        if($this->canDo->get('core.edit'))
        $html .= '&nbsp;&bull;&nbsp;'.JHtml::link(JRoute::_('&task=edit'), jgettext('Edit'), $active);

        $active =('versions' == $task) ? $activeS : '';
        $html .= '&nbsp;&bull;&nbsp;'.JHtml::link(JRoute::_('&task=versions'), jgettext('Version history'), $active);

        $html .= '</div>';

        return $html;
    }//function

    protected function getDiffTable($origCode, $newCode, $showAll = true)
    {
        $codeOrig = explode("\n", htmlspecialchars($origCode));
        $codeNew = explode("\n", htmlspecialchars($newCode));

        JLoader::register('Diff', JPATH_COMPONENT_SITE.'/helpers/DifferenceEngine.php');

        //--we are adding a blank line to the end.. this is somewhat 'required' by PHPdiff
        if($codeOrig[count($codeOrig) - 1] != '')
        {
            $codeOrig[] = '';
        }

        if($codeNew[count($codeNew) - 1] != '')
        {
            $codeNew[] = '';
        }

        $dwDiff = new Diff($codeOrig, $codeNew);
        $dwFormatter = new TableDiffFormatter();

        //-- Small hack to display the whole file - :|
        if($showAll)
        {
            $dwFormatter->leading_context_lines = 99999;
            $dwFormatter->trailing_context_lines = 99999;
        }

        return $dwFormatter->format($dwDiff);
    }//function

}//class
