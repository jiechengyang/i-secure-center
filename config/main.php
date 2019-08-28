<?php
/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 14:35
 */

return [
    'components' => [
        'iSecureCenter' => [
            'class' => \jcore\iSecureCenter\iSecureCenter::class,
            'version' => 'iSecure Center V1.1.0',
            'host' => 'https://192.168.32.13/',//your self host
            'ak' => [
                'hikvideo_icenter_appkey' => '',//api网关配置合作方 合作方appkey
                'hikvideo_icenter_secret' => '',//api网关配置合作方 合作方secret
            ],
            'on beforeSend' => function ($event) {
                //TODO: 接口发送前事件
            },
            'on afterSend' => function ($event) {
                //TODO: 接口发送后事件
            }
        ]
    ]
];
