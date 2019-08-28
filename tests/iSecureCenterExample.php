<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 14:57
 */

namespace jcore\iSecureCenter\tests;

use Yii;
use PHPUnit\Framework\TestCase;

final class iSecureCenterExample extends TestCase
{
    public function testCannotBeShow()
    {
        $this->expectOutputString(Yii::$app->get('i-secure-center')->hasMethod('send'));
        print Yii::$app->get('i-secure-center')->hasMethod('send');
    }
}