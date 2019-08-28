<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 14:34
 */

namespace jcore\iSecureCenter;

use yii\base\Event;

class MessageEvent extends Event
{
    public $url;

    public $method;

    public $message;

    public $response;

    public $isValid = true;
}