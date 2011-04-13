li3_sitemap is a lightweight sitemap generator for the lithium php framework.

#Installation

Getting the source

    cd /path/to/app/libraries
    git clone git://github.com/masom/li3_sitemap.git


Minimal Configuration

File: app/config/bootstrap/libraries.php
    Libraries::add('li3_sitemap', array(
	'sitemap'=> array(
		'controllers' => array(
			'app\controllers\Posts' => array(
				'models'=> array(
					'app\models\Posts'
				)
			)
		)
    )));

You can also pass options and configuration values:

    Libraries::add('li3_sitemap', array(
	'sitemap'=> array(
		/** Load custom sitemap layout/view */
		'view'=> array(
			'controller'=>'sitemaps',
			'layout' => 'sitemap',
			'type'=>'xml'
		),
		'controllers' => array(
			'app\controllers\Posts' => array(
				'models' => array('app\models\Posts')
			)
		)
    )));



Models can also be configured:

    Libraries::add('li3_sitemap', array(
        'sitemap'=> array(
                'controllers' => array(
                        'app\controllers\Posts' => array(
                                'models'=> array(
					'app\models\Posts' => array(
						'conditions' => array(
							'published' => true
						),
						'fields' => array(
							'_id',
							'name',
							'updated'
						),
						'order' => array(
							'created' => 'desc'
						)
					)
				)
                        )
                )
    )));

* Output:

    <?xml version="1.0" encoding="UTF-8"?> 
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
        <url> 
            <loc>http://example/</loc> 
            <lastmod>2011-04-13T16:25:57-04:00</lastmod> 
            <changefreq>daily</changefreq> 
            <priority>0.8</priority> 
	</url> 
        <url> 
            <loc>http://example/posts/345</loc> 
            <lastmod>2011-04-12T14:04:26-04:00</lastmod>
        </url> 
    </urlset>
