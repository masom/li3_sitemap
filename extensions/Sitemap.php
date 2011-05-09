<?php 
namespace li3_sitemap\extensions;

class Sitemap extends \lithium\core\StaticObject{

	private static function _controllerParser(array $config){
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
	}
	
	public static function generate(array $config){
		if (!isset($config['sitemap'])){
			throw new \Exception("`sitemap` configuration for li3_sitemap must be set.");
		}

		if (!isset($config['sitemap']['controllers'])){
			throw new \Exception("`controllers` configuration for li3_sitemap['sitemap'] must be set.");
		}

		$controllers = $config['sitemap']['controllers'];
		$sitemap = array();
		foreach ($controllers as $k=>$v){
			if (is_array($v)){
				//$k is the controller, $v are the models to map
				$class = explode('\\', (is_string($k) ? $k : get_class($k)));
				$sitemap[$class[count($class) - 1]] = self::_controllerParser($v['models']);
			}else{
				//$v is the controller
				$class = explode('\\', (is_string($v) ? $v : get_class($v)));
				$sitemap[$class[count($class) - 1]] = null;
			}
		}
		return $sitemap;
	}
	public static function configureView($request, array $config){
		$defautlType = isset($config['sitemap']['type']) ? $config['sitemap']['type'] : "html";
		$type = $request->type ?: $defautlType;
		
		$_defaults = array(
			'controller' => 'sitemaps',
			'library' => 'li3_sitemap',
			'template' => "index",
			'type' => $type,
			'layout' => 'default',
			'request' => $request
		);

		$options = array();

		if (isset($config['sitemap']['view']) && is_array($config['sitemap']['view'])){
			$options = $config['sitemap']['view'];
			unset($_defaults['library']);
		}
		$options += $_defaults;
		return $options;	
	}
}

?>