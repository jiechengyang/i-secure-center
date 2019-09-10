<?php

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/8/27 0027
 * Time: 下午 16:49
 */

namespace jcore\iSecureCenter;

use common\components\hk\Error;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;

class ArtemisHttpClient extends Client
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->on(self::EVENT_BEFORE_SEND, function (RequestEvent $event) {
            $request = $event->request;
            $contentMd5 = '';
            if (is_array($request->getData()) && !empty($request->getData())) {
                $data = Json::encode($request->getData());
                $request->setContent($data);
                $contentMd5 = base64_encode(md5($data, true));
            } elseif (is_string($request->getData())) {
                $request->setContent($request->getData());
            }

            $httpHeaders = [
                'Http Method' => strtoupper($request->getMethod()),
                'Accept' => '*/*',
                'Content-Type' => 'application/json',
                'Content-Md5' => $contentMd5,
                'Date' => $this->getGmtTime()
            ];
            $request->addHeaders($httpHeaders);
            $headers = $request->getHeaders();
            $secret = $headers['X-Ca-Secret'];
            $headerString = $this->getHeaderFormat($headers);
            Yii::info('###i-secure-center sign before string is:' . $headerString . '###', __METHOD__);
            unset($headers['Http Method'], $headers['X-Ca-Secret'], $headers['X-Ca-Url']);
            $signature = base64_encode(hash_hmac('sha256', $headerString, $secret, true));
            Yii::info('###i-secure-center sign after string is:' . $signature . '###', __METHOD__);
            $headers['X-Ca-Signature'] = $signature;
            $request->setHeaders($headers);
        });

        $this->on(self::EVENT_AFTER_SEND, function (RequestEvent $event) {
            if ($event->response->getStatusCode() != 200) {
                $res = [
                    'code' => 1001,
                    'message' => '接口请求失败了',
                    'data' => ''
                ];
            } else {
                $data = is_string($event->response->getData()) ? Json::decode($event->response->getData(), true) : $event->response->getData();
                if ($data['code'] !== '0') {
                    $res = [
                        'code' => 1001,
                        'message' => Error::errMsg($data['code']),
                        'data' => ''
                    ];
                } else {
                    $res = [
                        'code' => 1000,
                        'message' => Error::errMsg($data['code']),
                        'data' => $data['data']
                    ];
                }
            }

            $event->response->setData($res);
        });
    }

    private function getHeaderFormat($headers)
    {
        $defaultHeaderString = $headers['Http Method'] . "\n"
            . $headers['Accept'] . "\n"
            . trim($headers['Content-Md5']) . "\n"
            . $headers['Content-Type'] . "\n"
            . $headers['Date'] . "\n";
        $customHeaderString = 'x-ca-key:' . $headers['X-Ca-Key'] . "\n";

        return $defaultHeaderString . $customHeaderString . $headers['X-Ca-Url'];
    }

    private function getUtcTime()
    {
        date_default_timezone_set('UTC');
        $timestamp = new \DateTime();
        $timeStr = $timestamp->format("Y-m-d\TH:i:s\Z");
        return $timeStr;
    }

    private function getGmtTime()
    {
        return gmdate('D, d M Y H:i:s T');
    }
}