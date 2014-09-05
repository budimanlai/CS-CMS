<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$backend = dirname(dirname(__FILE__));
$frontend = dirname($backend);
Yii::setPathOfAlias('backend', $backend);
        
return array(
    'basePath'=> $frontend,
    'name'=> 'Backend - IP & TRIPS - Public Health',
    'theme'=>'backend',
    'controllerPath' => $backend.'/controllers',
    'runtimePath' => $backend.'/runtime',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'backend.models.*',
        'backend.components.*',
        'application.models.*',
        'application.components.*',
        'bootstrap.*',
        'bootstrap.components.*',
        'bootstrap.models.*',
        'bootstrap.controllers.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'bootstrap.extensions.*',
        'application.modules.users.models.*',
        'application.modules.users.extensions.CAccessPage',
        'application.modules.apiaccess.models.*',
        'application.vendor.Utilities',
        'application.extensions.easyimage.EasyImage'
    ),
    'aliases' => array(
        'bootstrap' => 'application.modules.bootstrap',
        'csboot' => 'webroot.themes.backend.widgets',
    ),
    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'apiaccess',
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ),
        'users',
        'gii'=>array(
            'generatorPaths' => array('bootstrap.gii'),
            'class' => 'system.gii.GiiModule',
            'password' => '123',
            'ipFilters' => array('127.0.0.1','::1'),
            /*
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
             */
        ),
    ),

    // appcation components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        'request'=>array(
            'enableCsrfValidation'=>false,
        ),
        'bsHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ),
        'easyImage' => array(
            'class' => 'application.extensions.easyimage.EasyImage',
            //'driver' => 'GD',
            //'quality' => 100,
            //'cachePath' => '/assets/easyimage/',
            //'cacheTime' => 2592000,
            //'retinaSupport' => false,
        ),
        'db'=> require($frontend . '/config/dbconfig.php'),
        'errorHandler'=>array(
        // use 'site/error' action to display errors
        'errorAction'=>'site/error',
    ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(array('class'=>'CFileLogRoute','levels'=>'error, warning',),
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=> require($frontend . '/config/params.php'),
);