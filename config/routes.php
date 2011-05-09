<?php
/**
 * li3_sitemap: Lightweight sitemap generator for Lithium
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\Libraries;
use lithium\net\http\Router;
use li3_sitemap\extensions\Sitemap;

$config = Libraries::get('li3_sitemap');
$base = isset($config['sitemap']['url']) ? $config['url'] : '/sitemap';
$sitemap =function($request){ return Sitemap::render($request);};
Router::connect("{$base}.{:type}", array(), $sitemap);
Router::connect($base, array(), $sitemap);
?>