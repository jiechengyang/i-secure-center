<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 15:38
 */

spl_autoload_register(function($className) {
   $className = str_replace("\\", '/', $className);
   $scriptName = str_replace('jcore/iSecureCenter', '', $className) . '.php';
   $fullName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src/' . $scriptName;
   is_file($fullName) && require_once $fullName;
});