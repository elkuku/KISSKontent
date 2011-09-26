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

function KISSKontentBuildRoute(&$query)
{
    $segments = array();

    if(isset($query['p']))
    {
        $segments[] = $query['p'];

        unset($query['p']);
    }

    return $segments;
}//function

function KISSKontentParseRoute($segments)
{
    $vars = array();

    if(count($segments))
    $vars['p'] = implode('/', $segments);

    return $vars;
}//function
