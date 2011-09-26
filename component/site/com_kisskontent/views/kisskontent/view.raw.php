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
    /**
    * KISSKontent view display method.
    *
    * @param string $tpl The name of the template file to parse;
    *
    * @return void
    */
    public function display($tpl = null)
    {
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

        parent::display($tpl);
    }//function

    protected function diff()
    {
        $this->diffAll =(JRequest::getInt('diffAll')) ? true : false;

        $this->diff = KISSKontentHelper::getDiffFromRequest();

        $this->setLayout('diffraw');
    }//function

    protected function defaultTask()
    {
        $this->setLayout('raw');
    }//function
}//class
