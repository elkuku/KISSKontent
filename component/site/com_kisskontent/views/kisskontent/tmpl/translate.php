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

$useGoogle = false;
?>

<div class="kissKontent<?php echo $this->pageclass_sfx;?>">

<noscript><?php echo jgettext('You should enable JavaScript'); ?></noscript>
<?php

if($useGoogle) :
JFactory::getDocument()->addScript('http://www.google.com/jsapi');
?>
    <div id="google_loader">
        <span class="ajax_loading">
            <?php echo jgettext('Loading Google Translation API'); ?>
        </span>
    </div>

    <script type="text/javascript">
        google.load('language', '1');
//        var gbranding_displayed = false;
//        $('google_loader').innerHTML = '';
    </script>
<?php endif;?>
<script type="text/javascript">
    var KISSTranslator = new KISSTranslator();
//$('translation').focus();
</script>

<?php echo $this->menu(); ?>

<form method="post">

    <h1><?php echo jgettext('Translate');?></h1>

    <!-- Original  -->

    <?php echo $this->content->path; ?>
    <h2><?php echo $this->content->page; ?></h2>

    <?php
    //-- ACL check
    if( ! $this->canDo->get('core.translate'))
    {
        echo '<p>'.jgettext('You are not allowed to translate Kontent pages.').'</p>';
        echo '</div>';

        return;
    }
    ?>

    <?php if($this->missingTranslations) : ?>
        <p><?php echo jgettext('You have to translate the following Kontent items first'); ?></p>
        <ul>
        <?
        $l = true;

        foreach($this->missingTranslations as $item) :
            echo '<li>';
            echo($l)
            ? JHtml::link(KISSKontentHelper::getLink($item.'&task=translate')
            , sprintf(jgettext('Translate: %s'), $item))
            : $item;
            echo '</li>';

            $l = false;
        endforeach;

        echo '</div>';

        return;
        ?>
        </ul>
    <?php endif; ?>

    <?php echo KISSKontentHelper::drawFlag($this->content->lang); ?>
    <select name="origLang" onchange="KISSTranslator.loadTranslation(this.value, 'origText');">
        <?php echo $this->lists['origLang']; ?>
    </select>

    <br />

    <input id="origTitle" type="hidden" value="<?php echo $this->content->page; ?>" />

    <!-- Unescaped used to fetch it with js - @todo look for a better solution.. -->
    <div id="origText" style="display: none"><?php echo $this->content->text; ?></div>

    <!-- Escaped -->
    <div id="origTextEsc">
        <?php echo nl2br(htmlspecialchars($this->content->text)); ?>
    </div>

    <!-- Translation  -->

    <br />
    <hr />

    <?php echo KISSKontentHelper::drawFlag($this->translation->lang); ?>

    <?php echo $this->translation->path; ?>

    <input type="hidden" name="transPath" value="<?php echo $this->translation->path; ?>" />

    <?php if($this->translation->page) : ?>
        <h2><?php echo $this->translation->page; ?></h2>
        <input name="transTitle" type="hidden" value="<?php echo $this->translation->page; ?>" />
    <?php else :?>
        <br />
        <input name="transTitle" id="transTitle" type="text" style="font-size: 1.3em;"
        value="<?php echo $this->translation->page; ?>" />
    <?php endif; ?>

    <?php if( ! $this->translation->id) : ?>
        <?php if('default' != strtolower($this->content->page)) : ?>
            <input type="button" onclick="document.id('transTitle').value = document.id('origTitle').value;"
            value="<?php echo jgettext('Copy title'); ?>" />
        <?php endif; ?>

        <?php if($useGoogle) : ?>
            <input type="button" onclick="googleTranslate('origTitle', 'transTitle');"
            value="<?php echo jgettext('Google translate title'); ?>" />

            <span id="gtranslate_branding" style="float: right; padding-left: 0.5em;"></span>
            <a href="javascript:;" accesskey="g"
            onclick="KISSTranslator.google_translate(<?php
            echo "'{$this->content->lang}','{$this->translation->lang}','origTitle','transTitle'";
            ?>);">
            <span class="icon-16-copytrans"></span>
            <?php echo jgettext('Google translate'); ?>
                            </a>
        <?php endif; ?>

    <?php endif; ?>

    <input type="button" onclick="document.id('transText').value = document.id('origText').innerHTML;"
    value="<?php echo jgettext('Copy text'); ?>" />

    <?php if($useGoogle) : ?>
        <a href="javascript:;" accesskey="g"
        onclick="KISSTranslator.google_translate(<?php
        echo "'{$this->content->lang}','{$this->translation->lang}','origTextEsc','transText'";
        ?>);">
        <span class="icon-16-copytrans"></span>
        <?php echo jgettext('Google translate'); ?>
        </a>
        <br />
    <?php endif; ?>

    <textarea name="content" id="transText" rows="10"
    style="width: 100%"><?php echo $this->translation->text; ?></textarea>

    <p>
        <?php echo jgettext('Summary:'); ?> <input type="text" style="width: 80%" name="summary" />
    </p>

    <input type="submit" value="<?php echo jgettext('Translate'); ?>" />

    <input type="hidden" name="id" value="<?php echo $this->translation->id; ?>" />
    <input type="hidden" name="id_kiss" value="<?php echo $this->content->id; ?>" />
    <input type="hidden" name="kissLang" value="<?php echo $this->translation->lang; ?>" />
    <input type="hidden" name="task" value="dotranslate" />

    <?php echo JHtml::_('form.token'); ?>

</form>

</div>

<?php echo KISSKontentHelper::footer();
var_dump($this->translation);

echo str_repeat('<br />', 5);