<?php
class KuKuKontentModelKuKuKontent extends JModel
{
    /**
     *
     * Enter description here ...
     *
     * @return KuKuKontent
     */
    function getContent()
    {
        $content = new KuKuKontent;

        $p = JRequest::getString('p');

        if( ! $p)
        {
            $content->text = self::getDefault();
            return $content;
        }

        $query = $this->_db->getQuery(true);

        $query->from('#__kukukontent');
        $query->select('*');
        $query->where('title='.$this->_db->quote($p));

        $this->_db->setQuery($query);

        $c = $this->_db->loadObject();

        $content->path = $p;

        if($c)
        {
            $content->text = $c->text;
        }
        else
        {
            //$content->text = 'NEW CONTENT...'.$p;
        }

        return $content;
    }//function

    protected static function getDefault()
    {
        $tag = JFactory::getLanguage()->getTag();

        $path = JPATH_COMPONENT_SITE.'/demo';

        if(JFile::exists($path.'/'.$tag.'.md'))
        {
            $content = JFile::read($path.'/'.$tag.'.md');
        }
        else if(JFile::exists($path.'/en-GB.md'))
        {
            $content = JFile::read($path.'/en-GB.md');
        }
        else
        {
            $content = 'Hallo [[echo]](a/b/c/d/e/f/huhu) [[den gibt\'s nüsch]](gar/nixda) und [hier](http://ga.ga)';//@todo create simple default
        }

        return ($content) ?: '';
    }//function
}//class

class KuKuKontent
{
    public $path = '';
    public $text = '';
}
