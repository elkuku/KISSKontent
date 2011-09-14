<?php
// No direct access allowed to this file
defined('_JEXEC') || die('=;)');

class KuKuKontentHelper
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
        ? 'com_kukukontent'
        : 'com_kukukontent.kontent.'.(int)$kontentId;

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

    public static function doInternalAnchors($text)
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
        'KuKuKontentHelper::doAnchorsCallback', $text);

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
        'KuKuKontentHelper::doAnchorsCallback', $text);

        return $text;
    }//function

    /**
     *
     * Enter description here ...
     * @param unknown_type $matches
     */
    protected function doAnchorsCallback($matches)
    {
        $whole_match = $matches[1];

        $link_text = trim($matches[2], '/');

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

        $url = self::encodeAttribute(self::getLink($url));

        $result = '<a href="'.$url.'" class="internal'.$red.'"';

        $redAdvise =($red) ? jgettext('Click to create this page...') : '';

        $result .=(isset($title) || $redAdvise)
        ? ' title="'.self::encodeAttribute($redAdvise.$title).'"'
        : '';

        $result .= ">$link_text</a>";

        return $result;
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

    public static function getLink($text)
    {
        static $id;

        if( ! $id)
        {
            //-- Hey mom can I have my J! Itemid(tm) plz......

            $menus = JFactory::getApplication()->getMenu('site');

            $cId = JComponentHelper::getComponent('com_kukukontent')->id;

            $items = $menus->getItems('component_id', $cId);

            foreach($items as $item)
            {
                if(isset($item->query)
                && isset($item->query['view'])
                && 'kukukontent' == $item->query['view'])
                {
                    $id = $item->id;

                    break;
                }
            }//foreach

            if( ! $id)
            {
                $active = $menus->getActive();

                $id =($active) ? $active->id : 1;
            }
        }

        $raw = $text;

        if(0 === strpos($text, '/'))
        {
            // The text starts with a "/" - This is a relative internal link.
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
        $link .= 'index.php?option=com_kukukontent';

        $link .=($id) ? '&Itemid='.$id : '&view=kukukontent';

        $link .= '&p='.$parsed;

        return JRoute::_($link);
    }//function

    public static function isLink($link)
    {
        static $query, $db;

        if( ! $query)
        {
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query->from('#__kukukontent');
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
}//class
