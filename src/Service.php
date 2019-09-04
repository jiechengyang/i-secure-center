<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/2 0002
 * Time: 下午 15:50
 */

namespace jcore\iSecureCenter;

use Yii;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

class Service
{
    private static $_instance;

    public $baseUri;

    public $artemisPath;

    public $appKey;

    public $appSecret;

    public $xCaSignatureHeaders = 'x-ca-key';

    public $requestTimeout = 5;

    public function __construct($host, $artemisPath, $appKey, $appSecret)
    {
        $this->baseUri = $host . $artemisPath;
        $this->artemisPath = $artemisPath;
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }

    public static function getInstance($host, $artemisPath, $appKey, $appSecret)
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self($host, $artemisPath, $appKey, $appSecret);
        }

        return self::$_instance;
    }

    public function send($class, $action, $message = [])
    {
        if (is_string($class)) {
            $reflection = new \ReflectionClass($class);
            $class = $reflection->newInstanceArgs();
        } elseif (!is_object($class)) {
            return [
                'code' => 1001,
                'message' => '未定义接口类',
                'data' => ''
            ];
        }

        if (!$class->runAction($action, $message)) {
            return [
                'code' => 1001,
                'message' => '接口类未定义方法',
                'data' => ''
            ];
        }

        $message = $class->getRequestBody();
        if (!empty($class->getError())) {
            return [
                'code' => 1001,
                'message' => $class->getError(),
                'data' => ''
            ];
        }

        $uri = $class->getUri($action);
        if (empty($uri)) {
            return [
                'code' => 1001,
                'message' => '找不到接口地址',
                'data' => ''
            ];
        }

        $client = new ArtemisHttpClient([
            'baseUrl' => $this->baseUri
        ]);

        $headers = [
            'X-Ca-Key' => $this->appKey,
            'X-Ca-Url' => $this->artemisPath . $uri, //$this->host .
            'X-Ca-Secret' => $this->appSecret,
            'X-Ca-Signature-Headers' => $this->xCaSignatureHeaders
        ];

        $request = $client->createRequest()
//            ->setFormat(ArtemisHttpClient::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl($uri)
            ->setHeaders($headers)
            ->setOptions(['timeout' => $this->requestTimeout])
            ->setData($message);

        return $request->send();
    }
}