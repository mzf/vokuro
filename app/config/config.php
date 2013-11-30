<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'     => 'Mysql',
		'host'        => '127.0.0.1',
		'username'    => 'root',
		'password'    => '',
		'dbname'      => 'phalconza',
	),
	'application' => array(
		'controllersDir' => __DIR__ . '/../../app/controllers/',
		'modelsDir'      => __DIR__ . '/../../app/models/',
		'formsDir'       => __DIR__ . '/../../app/forms/',
		'viewsDir'       => __DIR__ . '/../../app/views/',
		'libraryDir'     => __DIR__ . '/../../app/library/',
		'pluginsDir'     => __DIR__ . '/../../app/plugins/',
		'cacheDir'       => __DIR__ . '/../../app/cache/',
		'baseUri'        => '/',
		'publicUrl'		 => '01phalconza.loc',
		'cryptSalt'		 => '$9gi4o$.f#21'
	),
	'mail' => array(
		'fromName' => 'Skidonza',
		'fromEmail' => 'skidonza@gmail.com',
		'smtp' => array(
			'server' => 'smtp.gmail.com',
			'port' => 587,
			'security' => 'tls',
			'username' => 'skidonza',
			'password' => '12skidonza12!',
		)
	),
	'amazon' => array(
		'AWSAccessKeyId' => "",
		'AWSSecretKey' => ""
	)
));
