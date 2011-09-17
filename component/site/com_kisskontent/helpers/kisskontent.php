<?php
// No direct access allowed to this file
defined('_JEXEC') || die('=;)');

class KISSKontentHelper
{
    const NESTED_BRACKETS_REGEX = '(?>[^\[\]]+|\[(?>[^\[\]]+|\[(?>[^\[\]]+|\[(?>[^\[\]]+|\[(?>[^\[\]]+|\[(?>[^\[\]]+|\[\])*\])*\])*\])*\])*\])*';
    const NESTED_URL_PARENTHESIS_REGEX = '(?>[^()\s]+|\((?>[^()\s]+|\((?>[^()\s]+|\((?>[^()\s]+|\((?>\)))*(?>\)))*(?>\)))*(?>\)))*';

    /**
     * @var string Our path.
     */
    protected static $p = '';

    public static function getActions($kontentId = 0)
    {
        //--- @TODO implement "real" ACL
        static $result;

        if($result)
        return $result;

        $user = JFactory::getUser();

        $result	= new JObject;

        $assetName =(empty($kontentId))
        ? 'com_kisskontent'
        : 'com_kisskontent.kontent.'.(int)$kontentId;

        //         $actions = array('core.admin', 'core.manage', 'core.create'
        //         , 'core.edit', 'core.delete');
        $actions = array('core.create', 'core.edit', 'core.delete');

        $x =($user->guest) ? false : true;//@todo häck.. all registered user allowed everything

        foreach($actions as $action)
        {
            //             $result->set($action, $user->authorise($action, $assetName));
            $result->set($action, $x);
        }//foreach

        return $result;
    }//function

    public static function preParse($string)
    {
        $string = self::doAnchors($string);
        $string = self::doInternalAnchors($string);

        return $string;
    }//function

    protected static function doAnchors($text)
    {
        return preg_replace_callback('@\s(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.\-]*(\?\S+)?)?)?)\s@'
        , 'KISSKontentHelper::doAnchorsCallback', $text);

    }//function

    protected static function doInternalAnchors($text)
    {
        self::$p = JRequest::getString('p');

        //
        // Inline-style internal links: [[link text]](url "optional title")
        // @add KuKu
        //
        $text = preg_replace_callback('{
    	(				# wrap whole match in $1
    			  \[\[
    				('.self::NESTED_BRACKETS_REGEX.')	# link text = $2
    			  \]\]
    			  \(			# literal paren
    				[ ]*
    				(?:
    					<(\S*)>	# href = $3
    				|
    					('.self::NESTED_URL_PARENTHESIS_REGEX.')	# href = $4
    				)
    				[ ]*
    				(			# $5
    				  ([\'"])	# quote char = $6
    				  (.*?)		# Title = $7
    				  \6		# matching quote
    				  [ ]*	# ignore any spaces/tabs between closing quote and )
    				)?			# title is optional
    			  \)
    			)
    			}xs',
        'KISSKontentHelper::doInternalAnchorsCallback', $text);

        //
        // Next, inline-style internal links: [[link text]]
        // @add KuKu
        //
        $text = preg_replace_callback('{
    		(				# wrap whole match in $1
    			\[\[
    				('.self::NESTED_BRACKETS_REGEX.')	# link text = $2
    			\]\]
    		)
    		}xs',
        'KISSKontentHelper::doInternalAnchorsCallback', $text);

        return $text;
    }//function

    protected static function doAnchorsCallback($matches)
    {
        $url = $matches[1];
        $text = $matches[2];

        if(strlen($text) > 20)
        {
            $text = substr($text, 0, 10).'...'.substr($text, strlen($text) - 10);
        }

        $attribs = array(
        'class' => 'external'
        );

        return ' '.JHtml::link($url, $text, $attribs).' ';
    }//function

    /**
     *
     * Enter description here ...
     * @param unknown_type $matches
     */
    protected static function doInternalAnchorsCallback($matches)
    {
        $whole_match = $matches[1];

        $text = trim($matches[2], '/');

        if(count($matches) > 3)
        {
            $url = $matches[3] == '' ? $matches[4] : $matches[3];
            $title =& $matches[7];
        }
        else
        {
            $url = $matches[2];
            $title = '';
        }

        $red =(self::isLink($url)) ? '' : ' redlink';

        $attribs = 'class="internal'.$red.'"';

        $url = self::encodeAttribute(self::getLink($url));

        $redAdvise =($red) ? jgettext('Click to create this page...') : '';

        $attribs .=(isset($title) || $redAdvise)
        ? ' title="'.self::encodeAttribute($redAdvise.$title).'"'
        : '';

        return JHtml::link($url, $text, $attribs);
    }//function

    protected static function encodeAttribute($text)
    {
        //
        // Encode text for a double-quoted HTML attribute. This function
        // is *not* suitable for attributes enclosed in single quotes.
        //
        $text = self::encodeAmpsAndAngles($text);
        $text = str_replace('"', '&quot;', $text);

        return $text;
    }//function

    protected static function encodeAmpsAndAngles($text)
    {
        //
        // Smart processing for ampersands and angle brackets that need to
        // be encoded. Valid character entities are left alone.
        // Ampersand-encoding based entirely on Nat Irons's Amputator
        // MT plugin: <http://bumppo.net/projects/amputator/>
        $text = preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/',
                '&amp;', $text);

        // Encode remaining <'s
        $text = str_replace('<', '&lt;', $text);

        return $text;
    }//function

    public static function getLink($text, $add = '')
    {
        static $Itemid;

        if( ! $Itemid)
        {
            //-- Hey mom can I have my J! Itemid(tm) plz......

            $menus = JFactory::getApplication()->getMenu('site');

            $cId = JComponentHelper::getComponent('com_kisskontent')->id;

            $items = $menus->getItems('component_id', $cId);

            if($items)
            {
                foreach($items as $item)
                {
                    if(isset($item->query)
                    && isset($item->query['view'])
                    && 'kisskontent' == $item->query['view'])
                    {
                        $Itemid = $item->id;

                        break;
                    }
                }//foreach
            }

            if( ! $Itemid)
            {
                $active = $menus->getActive();

                $Itemid =($active) ? $active->id : 1;
            }
        }//-- end get Itemid...

        $raw = $text;

        if(0 === strpos($text, '/'))
        {
            //-- The text starts with a "/" - This is a relative internal link.
            //-- @todo add support for "../" syntax ¿
            $raw = self::$p.$text;
        }

        $parts = explode('/', $raw);

        $results = array();

        foreach($parts as $part)
        {
            $results[] = rawurlencode($part);
        }//foreach

        $parsed = implode('/', $results);

        $link = '';
        $link .= 'index.php?option=com_kisskontent';

        $link .=($Itemid) ? '&Itemid='.$Itemid : '&view=kisskontent';

        $link .= '&p='.$parsed;

        $link .= $add;

        return JRoute::_($link);
    }//function

    public static function isLink($link)
    {
        static $query, $db;

        if( ! $query)
        {
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query->from('#__kisskontent');
            $query->select('count(*)');
        }

        //-- If the link has a leading slash, it is relative
        $parsed =(0 === strpos($link, '/')) ? self::$p.$link : $link;

        $query->clear('where');
        $query->where('title='.$db->quote(urldecode($parsed)));

        $db->setQuery($query);

        $c = $db->loadResult();

        return ($c) ? true : false;
    }//function

    public static function getDiffLink($title, $v1, $v2)
    {
        $add = '&task=diff&amp;v1='.$v1.'&amp;v2='.$v2;

        return self::getLink($title, $add);
    }//function

    /**
     * Count the number of bytes of a given string.
     *
     * @author http://www.hashbangcode.com/blog/work-out-size-bytes-php-string-248.html
     * Input string is expected to be ASCII or UTF-8 encoded.
     * Warning: the function doesn't return the number of chars
     * in the string, but the number of bytes.
     * See http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
     * for information on UTF-8.
     *
     * @param string $str The string to compute number of bytes
     *
     * @return The length in bytes of the given string.
     */
    public static function strBytes($str)
    {
        // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT

        // Number of characters in string
        $strlen_var = strlen($str);

        // string bytes counter
        $d = 0;

        /*
         * Iterate over every character in the string,
        * escaping with a slash or encoding to UTF-8 where necessary
        */
        for($c = 0; $c < $strlen_var; ++$c)
        {
            $ord_var_c = ord($str{$c});

            switch(true)
            {
                case(($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                    // characters U-00000000 - U-0000007F (same as ASCII)
                    $d++;
                    break;
                case(($ord_var_c & 0xE0) == 0xC0):
                    // characters U-00000080 - U-000007FF, mask 110XXXXX
                    $d+=2;
                    break;
                case(($ord_var_c & 0xF0) == 0xE0):
                    // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                    $d+=3;
                    break;
                case(($ord_var_c & 0xF8) == 0xF0):
                    // characters U-00010000 - U-001FFFFF, mask 11110XXX
                    $d+=4;
                    break;
                case(($ord_var_c & 0xFC) == 0xF8):
                    // characters U-00200000 - U-03FFFFFF, mask 111110XX
                    $d+=5;
                    break;
                case(($ord_var_c & 0xFE) == 0xFC):
                    // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                    $d+=6;
                    break;
                default:
                    $d++;
            }//switch
        }//for

        return number_format($d);
    }//function
}//class
