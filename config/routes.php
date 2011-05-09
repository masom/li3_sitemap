<?php
/**
 * li3_sitemap: Lightweight sitemap generator for Lithium
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;
use lithium\template\View;
use lithium\core\Environment;
use li3_sitemap\extensions\Sitemap;

$config = Libraries::get('li3_sitemap');
$base = isset($config['sitemap']['url']) ? $config['url'] : '/sitemap';

$sitemap = function($request){

	$config = Libraries::get('li3_sitemap');	
	$sitemap = Sitemap::generate($config);
	$viewOptions = Sitemap::configureView($request, $config);
	$response = new Response(compact('request'));
	
	$view  = new View(
		array(

		    'paths' => array(
				'element' => '{:library}/views/elements/{:template}.{:type}.php',
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
		        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		    )
		));	
		
	$response->body = $view->render('all',	compact('sitemap'), $viewOptions);
	return $response;
};

Router::connect("{$base}.{:type}", array(), $sitemap);
Router::connect($base, array(), $sitemap);
?>