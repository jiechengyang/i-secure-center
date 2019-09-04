<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/2 0002
 * Time: 下午 17:58
 */

namespace jcore\iSecureCenter\api\resource;


use jcore\iSecureCenter\api\BaseApi;

class Videos extends BaseApi
{
    protected $_uriMap = [
        'getEncodeDeviceList' => '/api/resource/v1/encodeDevice/get',// 获取编码设备列表
        'searchEncodeDevice' => '/api/resource/v1/encodeDevice/search',// 查询编码设备列表
        'getEncodeDeviceSingle' => '/api/resource/v1/encodeDevice/single/get',// 获取单个编码设备信息
        'getDevicesubResources' => '/api/resource/v1/encodeDevice/subResources',// 根据区域获取下级编码设备列表
        'searchCameraList' => '/api/resource/v1/camera/advance/cameraList',// 查询监控点列表
        'getCameras' => '/api/resource/v1/cameras',// 分页获取监控点资源
        'getCamerasByRegionCode' => '/api/resource/v1/regions/regionIndexCode/cameras',// 分页获取监控点资源
        'getCameraByIndexCode' => '/api/resource/v1/cameras/indexCode',// 分页获取监控点资源
    ];

    public function getEncodeDeviceList($data)
    {
        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);
        return true;
    }


    /**
     * @param $data
     * @param $data['regionIndexCodes'] 区域编号 type Array
     * @param $data['name'] 名称
     * @param $data['containSubRegion'] 是否包含下级区域
     * @param $data['authCodes'] 权限码集合  type Array
     * @param $data['exactCondition'] 指定字段条件精确查询 type Object
     * @param $data['fieldValueNotNull'] 查询字段值不为空的字段名
     * @return bool
     */
    public function searchEncodeDevice($data)
    {
        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        empty($data['containSubRegion']) && $data['containSubRegion'] = true;
        empty($data['authCodes']) && $data['authCodes'][] = 'view';
        $this->setRequestBody($data);

        return true;
    }

    public function getEncodeDeviceSingle($data)
    {
        if (empty($data['resourceIndexCode'])) {
            $this->setError("资源编号必须提供");
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function getDevicesubResources($data)
    {
        if (empty($data['regionIndexCode'])) {
            $this->setError("父区域编号必须提供");
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 1;
        empty($data['authCodes']) && $data['authCodes'][] = 'view';

        $this->setRequestBody($data);

        return true;
    }


    /**
     * @param $data
     * @param $data['cameraIndexCodes'] 监控点唯一标识集 多个值使用英文逗号分隔 type string
     * @param $data['cameraName'] 监控点名称
     * @param $data['encodeDevIndexCode'] 所属编码设备唯一标识
     * @param $data['regionIndexCode'] 区域唯一标识
     * @param $data['isCascade'] 0：非级联 1：级联 2：不限（包括级联和非级联） 默认取值：2
     * @param $data['treeCode'] 树编号。综合安防平台当前未使用该字段。该字段预留
     * @return bool
     */
    public function searchCameraList($data)
    {
        if (strlen($data['encodeDevIndexCode']) > 64) {
            $this->setError('编码设备唯一标识最大长度64');
            return false;
        }

        if (strlen($data['regionIndexCode']) > 64) {
            $this->setError('区域唯一标识最大长度64');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        !isset($data['isCascade']) && $data['isCascade'] = 2;

        $this->setRequestBody($data);

        return true;
    }

    public function getCameras($data)
    {
        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }

    public function getCamerasByRegionCode($data)
    {
        if (empty($data['regionIndexCode'])) {
            $this->setError('区域编号必须提供');
            return false;
        }

        if (strlen($data['regionIndexCode']) > 64) {
            $this->setError('区域编号最大长度64');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }

    public function getCameraByIndexCode($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点编号必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }
}