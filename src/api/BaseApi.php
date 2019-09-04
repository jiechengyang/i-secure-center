<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/2 0002
 * Time: 下午 16:49
 */

namespace jcore\iSecureCenter\api;


abstract class BaseApi
{
    protected $error;

    protected $_uriMap;

    protected $requestUri;

    public $requestBody;

    /**
     * @return mixed
     */
    public function getUriMap()
    {
        return $this->_uriMap;
    }

    /**
     * @return mixed
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * @return mixed
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * @return mixed
     */
    public function getUri($action)
    {
        if (empty($action) || empty($this->_uriMap[$action])) {
            return null;
        }

        $this->requestUri = $this->_uriMap[$action];

        return $this->requestUri;
    }

    public function runAction($action, $message = [])
    {
        if (!is_callable([$this, $action])) {
            return false;
        }

        call_user_func([$this, $action], $message);

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getErrorFormat($glue = PHP_EOL)
    {
        return implode($glue, $this->getError());
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}