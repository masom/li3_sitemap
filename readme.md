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
				'models'=>'app\models\Posts'
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
				'models'=>'app\models\Posts'
			)
		)
    )));



Models can also be configured:

    Libraries::add('li3_sitemap', array(
        'sitemap'=> array(
                'controllers' => array(
                        'app\controllers\Posts' => array(
                                'models'=>'app\models\Posts' => array(
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
    )));

