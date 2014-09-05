<?php

class RssController extends Controller {
    
    public function actionIndex() {
        Yii::import('ext.feed.*');
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title= 'Technosoft Blog';
        $feed->description = 'This is test of creating a RSS 2.0 Feed';

        $feed->setImage('Testing RSS 2.0 EFeed class','http://www.technosoft.com.sg/assets/logo.png',
        'http://www.technosoft.com.sg/assets/logo.png');

        $feed->addChannelTag('language', 'en-us');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', 'http://blog.technosoft.com.sg/rss/index' );

        // * self reference
        //$feed->addChannelTag('atom:link','http://www.ramirezcobos.com/rss/');

        $model = Content::model()->findAll(array(
            'condition' => 'group_id = 1 AND status = "active"',
            'order' => 'create_datetime DESC',
            'limit' => 2
        ));
        
        if ($model != null) {
            foreach($model as $row) {
                $item = $feed->createNewItem();
 
                $item->title = $row->title;
                $item->link = Content::createAbsoulteUrl($row);
                $item->date = $row->create_datetime;
                $item->description = substr(strip_tags($row->long_description), 0, 500);
                // this is just a test!!
                //$item->setEncloser('http://www.tester.com', '1283629', 'audio/mpeg');

                $item->addTag('author', $row->mUser->username." <{$row->mUser->email}>");
                $item->addTag('guid', Content::createAbsoulteUrl($row), array('isPermaLink'=>'true'));

                $feed->addItem($item);
            }
        }
//        $item = $feed->createNewItem();
// 
//        $item->title = "first Feed";
//        $item->link = "http://www.yahoo.com";
//        $item->date = time();
//        $item->description = 'This is test of adding CDATA Encoded description <b>EFeed Extension</b>';
//        // this is just a test!!
//        //$item->setEncloser('http://www.tester.com', '1283629', 'audio/mpeg');
//
//        $item->addTag('author', 'thisisnot@myemail.com (Antonio Ramirez)');
//        $item->addTag('guid', 'http://www.ramirezcobos.com/',array('isPermaLink'=>'true'));
//
//        $feed->addItem($item);
        
        $feed->generateFeed();
        Yii::app()->end();
    }
}