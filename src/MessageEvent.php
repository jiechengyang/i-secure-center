<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/27 0027
 * Time: 下午 16:09
 */

namespace jcore\iSecureCenter;

use yii\base\Event;

class MessageEvent extends Event
{
    public $partner;

    public $apiClass;

    public $action;

    public $response;

    public $isValid = true;
}