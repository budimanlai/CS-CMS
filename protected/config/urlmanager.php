<?php

return array(
    'urlFormat'=>'path',
    'cacheID' => 'cache',
    'urlSuffix'=>'.html',
    'showScriptName' => false,
    'caseSensitive'=>false,
    'rules'=>array(
        'site/logout' => 'site/logout',
        'site/index' => 'content/byyear',
        '<year:\d+>/<month:\d+>' => 'content/byyear',
        'contact-us' => 'contact/index',
        'recent' => 'content/recent',
        'solution' => 'content/solution',
        'news-articles' => 'site/news',
        '<group>/<name>' => 'content/index',
        '<name>' => 'content/index',
        
        //action page
        '<action:(contact|login|logout)>/*' => 'site/<action>',
        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
    ),
);
?>