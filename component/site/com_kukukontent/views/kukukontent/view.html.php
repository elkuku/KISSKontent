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

    protected $canDo = false;

    protected $p = '';

    /**
     * KuKuKontent view display method.
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

        JHtml::_('stylesheet', 'com_kukukontent/kukukontent.css', array(), true);
        JHtml::_('script', 'com_kukukontent/kukukontent.js', array(), true);

        $this->canDo = KuKuKontentHelper::getActions();

        $this->setPathway();

        parent::display($tpl);

        return;
    }//function

    protected function save()
    {
        $this->defaultTask();
    }

    protected function defaultTask()
    {
        $this->content = $this->get('content');

        if( ! $this->content->text)
        {
            $this->setLayout('edit');

            return;
        }

        //-- Process internal links
        $this->content->text = KuKuKontentHelper::doInternalAnchors($this->content->text);

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
        JHtml::_('stylesheet', 'com_kukukontent/diff.css', array(), true);

        $this->versionOne = $this->get('versionOne');
        $this->versionTwo = $this->get('versionTwo');

        $this->diff = $this->getDiffTable($this->versionOne->text, $this->versionTwo->text);

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

        if( ! $items)
        return;// No pathway :(

        $parts = explode('/', $this->p);

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

    protected function menu()
    {
        $task = JRequest::getCmd('task');

        $html = '';

        $html .= '<div style="text-align: right">';

        $activeS =' class="active"';

        $active =('' == $task) ? $activeS : '';
        $html .= '<a'.$active.' href="'.JURI::current().'">'.jgettext('Read').'</a>';

        $active =('edit' == $task) ? $activeS : '';

        if($this->canDo->get('core.edit'))
        $html .= '&nbsp;&bull;&nbsp;<a'.$active.' href="'.JURI::current().'?task=edit'.'">'.jgettext('Edit').'</a>';

        $active =('versions' == $task) ? $activeS : '';
        $html .= '&nbsp;&bull;&nbsp;<a'.$active.' href="'.JURI::current().'?task=versions">'.jgettext('Version history').'</a>';
        $html .= '</div>';

        return $html;
    }//function

    protected function getDiffTable($origCode, $newCode, $showAll = true)
    {
        $codeOrig = explode("\n", htmlentities($origCode));
        $codeNew = explode("\n", htmlentities($newCode));

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
