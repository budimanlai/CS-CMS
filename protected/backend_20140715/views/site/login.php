login
<?php
$item = array(
    'Home',
    'Product' => array('create'),
    'Product2' => Yii::app()->createUrl('site/index', array('id' => 123))
);
echo "<pre>".print_r($item, true)."</pre>";
?>