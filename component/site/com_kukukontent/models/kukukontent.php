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
        $p = JRequest::getString('p', 'default');

        $table = $this->getTable();

        if('default' == $p)
        {
            try
            {
                $table->load(array('title' => 'default'));
            }
            catch (Exception $e)
            {
//                 $table->title = 'Default';
                $table->text = self::getDefault();
            }//try
        }
        else
        {
            try
            {
                $table->load(array('title' => $p));
            }
            catch (Exception $e)
            {
                $table->title = $p;
            }//try
        }

        $table->path = $table->title;

        return $table;
    }//function

    /**
     *
     * Enter description here ...
     */
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

    /**
     *
     * Enter description here ...
     */
    public function save()
    {
        $src = new stdClass;

        $src->id = JRequest::getInt('id', 0);
        $src->title = JRequest::getString('p', 'Default');
        $src->text = JRequest::getVar('content', '', 'post', 'none', JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        try
        {
            $this->getTable()
            ->bind($src)->check()->store();
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try
    }//function
}//class
