<?php
/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/28 0028
 * Time: 下午 14:35
 */

return [
    'components' => [
        'i-secure-center' => [
            'class' => \jcore\iSecureCenter\iSecureCenter::class,
            'version' => 'iSecure Center V1.1.0',
            'host' => 'https://127.0.0.1',
            'artemisPath' => '/artemis',
            'requestTimeout' => 10,
            'partners' => [
                //TODO: 多合作方配置 改成自己的
                'adminPartner' => [
                    'hikvideo_icenter_appkey' => '11111',
                    'hikvideo_icenter_secret' => '22222',
                ],
                'TestPartner' => [
                    'hikvideo_icenter_appkey' => '33333',
                    'hikvideo_icenter_secret' => '44444',
                ]
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
