<?php
/**
 * @package    KISSKontent
 * @subpackage Base
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

jimport('joomla.application.component.controller');

/**
 * KISSKontent Controller.
 *
 * @package    KISSKontent
 * @subpackage Controllers
 */
class KISSKontentControllerTranslator extends JController
{
    public function load()
    {
        $model = JModel::getInstance('KISSKontent', 'KISSKontentModel');

        $kontent = $model->getContent('default');

        if( ! $kontent->id)
        $kontent = $model->getContent();

        echo $kontent->text;
    }//function

    public function googleTranslate()
    {
        JLoader::register('googleTranslator', JPATH_COMPONENT_SITE.'/helpers/googletranslator.php');

        //     var_dump($_REQUEST);
        $text = JRequest::getVar('text');

        if( ! $text)
        {
            echo jgettext('Nothing to translate');

            return;
        }


        //     $source = implode($argv, ' ');

        $translator = new googleTranslator;
        $result = $translator->translate($text, "en", "zh-TW");

        //     echo "Translate from: $source" . PHP_EOL;
        echo $result;
    }//function
}//class
