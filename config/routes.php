<?php
use lithium\core\Libraries;
use lithium\action\Response;
use lithium\net\http\Router;
use lithium\net\http\Media;
use lithium\core\Environment;

$config = Libraries::get('li3_sitemap');
$base = isset($config['sitemap']['url']) ? $config['url'] : '/sitemap';

$sitemap = function($request){

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
			$fields = array('name','updated');
			$limit = 100;
			$options = compact('conditions','order','fields', 'limit');
			
			$model = $v;
			
			if(is_array($v)){
				//$k is model, $v is configuration
				$options = array_merge($options, $v);
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
			$sitemap[$class[count($class) - 1]] = $controllerParser($v['models']);
		}else{
			//$v is the controller
			$class = explode('\\', (is_string($v) ? $v : get_class($v)));
			$sitemap[$class[count($class) - 1]] = null;
		}
	}
	
	$defautlType = isset($config['sitemap']['type']) ? $config['sitemap']['type'] : "html";
	$type = $request->params->type ?: $defautlType;
	
	$options = array(
		'controller' => 'sitemaps',
		'library' => 'li3_sitemap',
		'template' => "index",
		'type' => $type,
		'layout' => 'default',
		'request' => $request
	);
	
	if(isset($config['sitemap']['view']) && is_array($config['sitemap']['view'])){
		unset($options['library']);
		$options = array_merge($options,$config['sitemap']['view']);
	}
	$response = new Response(compact('request'));
	Media::render($response, compact('sitemap'), $options);
	return $response;
};

Router::connect("{$base}.{:type}", array(), $sitemap);
Router::connect($base, array(), $sitemap);
?>