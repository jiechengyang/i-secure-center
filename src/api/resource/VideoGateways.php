<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/3 0003
 * Time: 上午 11:04
 */

namespace jcore\iSecureCenter\api\resource;

use jcore\iSecureCenter\api\BaseApi;

class VideoGateways extends BaseApi
{
    protected $_uriMap = [
        'getVideoRecordList' => '/api/nms/v1/record/list', // 根据监控点列表查询录像完整性结果
        'getVideoQualityList' => '/api/nms/v1/vqd/list', // 根据监控点列表查询视频质量诊断结果
        'getOnlineStorageDevice' => '/api/nms/v1/online/storage_device/get', //获取存储设备在线状态
        'getOnlineCameras' => '/api/nms/v1/online/camera/get',// 获取监控点在线状态
        'getOnlineEncodeDevice' => '/api/nms/v1/online/encode_device/get',// 获取编码设备在线状态
        'getOnlineDecodeDevice' => '/api/nms/v1/online/decode_device/get',
    ];


    /**
     * @param $data
     * @param $data['beginTime'] 录像开始日期，IOS8601格式--系统已经转过无需再转
     * @param $data['endTime'] 录像结束日期，IOS8601格式--系统已经转过无需再转
     * @param $data['indexCodes'] 监控点编号 type Array
     * @return bool
     */
    public function getVideoRecordList($data)
    {
        if (empty($data['beginTime'])) {
            $this->setError('录像开始日期必须提供');
            return false;
        }

        if (empty($data['endTime'])) {
            $this->setError('录像结束日期必须提供');
            return false;
        }

        if ($data['beginTime'] > $data['endTime']) {
            $this->setError('录像查询时间区间不对');
            return false;
        }

        if (empty($data['indexCodes']) || !is_array($data['indexCodes'])) {
            $this->setError('监控点编号至少提供一个');
            return false;
        }

        $data['beginTime'] = date(DATE_ISO8601, strtotime($data['beginTime']));
        $data['endTime'] = date(DATE_ISO8601, strtotime($data['endTime']));
        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }

    public function getVideoQualityList($data)
    {
        if (empty($data['indexCodes']) || !is_array($data['indexCodes'])) {
            $this->setError('监控点编号至少提供一个');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }


    /**
     * @param $data
     * @param $data['regionId'] 区域id
     * @param $data['ip'] ip
     * @param $data['indexCodes'] 编码列表 type Array
     * @param $data['status'] 状态，1-在线，0-离线，-1-未检测
     * @param $data['includeSubNode'] 是否包含下级
     * @return bool
     */
    public function getOnlineStorageDevice($data)
    {
        if (count($data['indexCodes']) > 500) {
            $this->setError('编码列表最大不超过500');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }

    /**
     * @param $data
     * @param $data['regionId'] 区域id
     * @param $data['ip'] ip
     * @param $data['indexCodes'] 编码列表 type Array
     * @param $data['status'] 状态，1-在线，0-离线，-1-未检测
     * @param $data['includeSubNode'] 是否包含下级
     * @return bool
     */
    public function getOnlineCameras($data)
    {
        if (count($data['indexCodes']) > 500) {
            $this->setError('编码列表最大不超过500');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 20;
        $this->setRequestBody($data);

        return true;
    }
}