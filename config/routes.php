<?php
use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;

$config = Libraries::get('li3_sitemap');
$base = isset($config['url']) ? $config['url'] : '/sitemap';
Router::connect($base, array('controller' => 'li3_sitemap.SiteMaps', 'action' => 'index'));
?>