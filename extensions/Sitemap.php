<?php 
namespace li3_sitemap\extensions;

use lithium\template\View;
use lithium\core\Environment;
use lithium\action\Response;
use lithium\core\Libraries;

class Sitemap extends \lithium\core\StaticObject{

	/**
	 * Renders the sitemap response based on the received request and configuration
	 * @param object $request The request object
	 * @throws Exception
	 * @return object Response
	 */
	public static function render($request){
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
	}
	/**
	 * Parses the controllers configured for the sitemap and fetches associated data.
	 * @param array $config The library configuration
	 * @return array Sitemap
	 */
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

	/**
	 * Generates the sitemap content
	 * @param array $config Library configuration
	 * @throws \Exception
	 * @return array Sitemap
	 */
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

	/**
	 * Configures the view to be rendered
	 * @param object $request The request object
	 * @param array $config Library configuration
	 * @return array View options
	 */
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