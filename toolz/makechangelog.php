#!/usr/bin/php
<?php
/**
 * An example command line application built on the Joomla Platform.
 *
 * To run this example, adjust the executable path above to suite your operating system,
 * make this file executable and run the file.
 *
 * Alternatively, run the file using:
 *
 * php -f run.php
 *
 * @package     Joomla.Examples
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// We are a valid Joomla entry point.
define('_JEXEC', 1);

define('NL', "\n");

// Setup the path related constants.
define('JPATH_BASE', dirname(__FILE__));

// Bootstrap the application.
require $_SERVER['JOOMLA_PLATFORM_PATH'].'/libraries/import.php';

jimport('joomla.application.cli');

// Register the markdown parser class so it's loaded when needed.
//JLoader::register('ElephantMarkdown', __DIR__.'/includes/markdown.php');

/**
 * An example command line application class.
 *
 * This application builds the HTML version of the Joomla Platform change log from the Github API
 * that is used in news annoucements.
 *
 * @package  Joomla.Examples
 */
class Changelog extends JCli
{
    /**
     * Execute the application.
     *
     * @return  void
     *
     * @since   11.3
     */
    public function execute()
    {
        // Import the JHttp class that will connect with the Github API.
        jimport('joomla.client.http');

        echo 'Starting...';

        $text = '';

        $text = '# Changelog forKISSKontent'.NL.NL;

        // Set the maximum number of pages (and runaway failsafe).
        $cutoff = 10;
        $page = 1;

        $list = '';

        while ($cutoff --)
        {
            // Get a page of issues.
            $list = $this->getCommits($page ++);

            // Check if we've gone past the last page.
            if( ! isset($list->commits))
            break;

            // Loop through each pull.
            foreach($list->commits as $commit)
            {
                $list .= '* '.$commit->committed_date;
                $list .= ' ['.$commit->message.'](https://github.com'.$commit->url.')'.PHP_EOL;
            }//foreach
        }//while

        $text .= $list;//ElephantMarkdown::parse($list);

        // Check if the output folder exists.
        if( ! is_dir('./docs'))
        mkdir('./docs');

        // Write the file.
        file_put_contents('./docs/changelog.md', $text);

        echo 'Finished =;)'.NL.NL;

        // Close normally.
        $this->close();
    }//function

    /**
     * Get a page of issue data.
     *
     * @param   integer  $page  The page number.
     *
     * @return  array
     *
     * @since   11.3
     */
    protected function getCommits($page)
    {
        $http = new JHttp;

        $r = $http->get(
            'http://github.com/api/v2/json/commits/list/elkuku/KISSKontent/master?page='.$page.'&per_page=100'
        );

        return json_decode($r->body);
    }//function
}//class

// Catch any exceptions thrown.
try
{
    JCli::getInstance('Changelog')->execute();
}
catch (Exception $e)
{
    // An exception has been caught, just echo the message.
    fwrite(STDOUT, $e->getMessage() . "\n");

    echo $e->getTraceAsString();

    exit($e->getCode());
}//try
