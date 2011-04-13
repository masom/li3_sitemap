<?php
use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;
use lithium\net\http\Media;

$config = Libraries::get('li3_sitemap');
$base = isset($config['sitemap']['url']) ? $config['url'] : '/sitemap';

Router::connect($base, array(), function($request){
	
	
	$config = Libraries::get('li3_sitemap');
	
	if(!isset($config['sitemap'])){
		throw new \Exception("`sitemap` configuration for li3_sitemap must be set.");
	}
	if(!isset($config['sitemap']['controllers'])){
		throw new \Exception("`controllers` configuration for li3_sitemap['sitemap'] must be set.");
	}

	$controllerParser = function($config){
		$map = array();
		foreach($config as $k => $v){
			
			$conditions = array();
			$orders = array();
			$fields = array('updated');
			$limit = 100;
			$options = compact('conditions','order','fields', 'limit');
			
			$model = $v;
			
			if(is_array($v)){
				//$k is model, $v is configuration
				$options += $v;
				$model = $k;
			}
			$class = explode('\\', (is_string($model) ? $model : get_class($model)));
			$map[$class[count($class) - 1]] = $model::all($options);
		}
		return $map;
	};
	
	$controllers = $config['sitemap']['controllers'];
	$sitemap = array();
	foreach($controllers as $k=>$v){
		if(is_array($v)){
			//$k is the controller, $v are the models to map
			$class = explode('\\', (is_string($k) ? $k : get_class($k)));
			$sitemap[$class[count($class) - 1]] = $controllerParser($v);
		}else{
			//$v is the controller
			$class = explode('\\', (is_string($v) ? $v : get_class($v)));
			$sitemap[$class[count($class) - 1]] = null;
		}
	}
	if(isset($config['sitemap']['type'])){
		$type = $config['sitemap']['type'];
	}else{
		Media::type('xml', 'text/xml', array());
		$type = 'xml';
	}
	
	$response = new Response(compact('request'));
	Media::render($response, compact('sitemap'), array(
		'controller' => 'sitemaps',
		'library' => 'li3_sitemap',
		'template' => "index",
		'type' => $type,
		'layout' => 'default',
		'request' => $request
	));
	return $response;
});
?>