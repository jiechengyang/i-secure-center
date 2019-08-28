<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 14:50
 */

$iSecureCenter = \Yii::$app->get('iSecureCenter');

//TODO: beforeSend 写入你的业务代码
$iSecureCenter->on(\jcore\iSecureCenter::EVENT_BEFORE_SEND, function($event) {

});

//TODO: afterSend 写入你的业务代码
$iSecureCenter->on(\jcore\iSecureCenter::EVENT_AFTER_SEND, function($event) {

});

//TODO: 填入url method（GET OR POST） Params
$res = $iSecureCenter->send('api/resource/v1/encodeDevice/get', 'POST', [
    'pageNo' => 1,
    'pageSize' => 1
]);