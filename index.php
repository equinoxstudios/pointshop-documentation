<?php

/*
 * This file is part of http://github.com/adamdburton/pointshop-documentation
 *
 * (c) Adam Burton <adam@equinox-studios.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if(!file_exists('./vendor/autoload.php'))
{
	die('Run composer install!');
}

DEFINE('PS_DOCS', true);

require './vendor/autoload.php';
require './utils.php';

date_default_timezone_set('Europe/London');

$config = json_decode(file_get_contents('config.json'), true);

$uri = $_SERVER['REQUEST_URI'];

$tree = buildTree($uri);
$page = findInTree($uri, $tree);

if(is_array($page) && !$page['file'])
{
	// findInTree returned an array but not a page, must be a folder
	
	$first_child = current($page['children']);
	
	if($first_child)
	{
		// Redirect to first child
		
		header('Location: ' . $first_child['full_uri']);
	}
	else
	{
		// No first child found, 404
		
		$page = null;
	}
}

renderPage(preparePage($page), $uri);