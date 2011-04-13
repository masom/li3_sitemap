<?php foreach($sitemap as $controller => $collections):?>
	<url>
		<loc><?=$this->url("{$controller}::index");?></loc>
		<lastmod>2005-01-01</lastmod>
		<changefreq>monthly</changefreq>
		<priority>0.8</priority>
	</url>
	<?php foreach($collections as $collection): 
		foreach($collection as $item): ?>
	<url>
		<loc><?=$this->url(array("{$controller}::view", "id"=>$item->id),array('absolute'=>true));?></loc>
		<lastmod><?=date('Y-m-d\TH:i:sP', $item->updated)?></lastmod>
	</url>
	<?php endforeach; endforeach;?>
<?php endforeach;?>