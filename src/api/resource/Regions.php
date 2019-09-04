<?php
/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/2 0002
 * Time: 下午 17:03
 */

namespace jcore\iSecureCenter\api\resource;

use jcore\iSecureCenter\api\BaseApi;

class Regions extends BaseApi
{
    const REGION_TYPE_LIST = [0, 1, 2, 9, 10, 11, 12];

    protected $_uriMap = [
        'getRegionsRoot' => '/api/resource/v1/regions/root',// 获取根区域信息
        'getRegionList' => '/api/irds/v2/region/nodesByParams',// 查询区域列表v2
        'getSubRegions' => '/api/resource/v1/regions/subRegions',// 根据区域编号获取下一级区域列表
        'updateSingleRegion' => '/api/resource/v1/region/single/update',// 获取根区域信息
        'batchAddRegion' => '/api/resource/v1/region/batch/add',// 获取根区域信息
    ];

    public function getRegionsRoot($data = [])
    {
        if (!is_array($data) && $data !== '{}') {
            $this->setError('接口必须至少传递一个空数组或者字符串{}');
            return false;
        } elseif (empty($data)) {
            $data = '{}';
        }

        $this->setRequestBody($data);

        return true;
    }

    public function getRegionList($data)
    {
        if (empty($data['resourceType'])) {
            $this->setError('资源类型必须提供');
            return false;
        }

        empty($data['pageNo']) && $data['pageNo'] = 1;
        empty($data['pageSize']) && $data['pageSize'] = 1;
        empty($data['isSubRegion']) && $data['isSubRegion'] = true;
        empty($data['authCodes']) && $data['authCodes'][] = 'view';
        $this->setRequestBody($data);

        return true;
    }

    public function getSubRegions($data)
    {
        if (empty($data['parentIndexCode'])) {
            $this->setError('父组织编号必须提供');
            return false;
        }

        if (strlen($data['parentIndexCode']) > 64) {
            $this->setError('父组织编号最大长度64');
            return false;
        }

        return true;
    }


    /**
     * @param $data
     * @param $data ['regionIndexCode'] 区域唯一标志
     * @param $data ['parentIndexCode'] 父区域唯一标识码
     * @param $data ['regionName'] 区域名称
     * @param $data ['regionCode'] 外码编码
     * @param $data ['regionType'] regionType 0-国标区域，1-雪亮工程区域，2-司法行政区域，9-自定义区域，10-普通区域， 11-级联区域，12-楼栋单元
     * @param $data ['regionCode'] 区域描述信息
     * @return bool
     */
    public function updateSingleRegion($data)
    {
        if (empty($data['regionIndexCode'])) {
            $this->setError('区域唯一标志必须提供');
            return false;
        }

        if (!in_array($data['regionType'], self::REGION_TYPE_LIST)) {
            $this->setError('区域类型不存在请参看官方文档的数据字典');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    /**
     * @param $data
     * @param $data[$i] ['clientId'] 区域唯一标志
     * @param $data[$i] ['parentIndexCode'] 三方用户需要通过clientid和regionIndexCode进行关联
     * @param $data[$i] ['regionIndexCode'] 区域唯一标志
     * @param $data[$i] ['regionName'] 区域名称，同一级不可重复 required unique
     * @param $data[$i] ['regionCode'] 外码编码
     * @param $data[$i] ['regionType'] 区域类型
     * @param $data[$i] ['description'] 区域描述信息
     * @return bool
     */
    public function batchAddRegion($data = [])
    {
        if (!is_array($data)) {
            $this->setError('区域数组格式错误');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }
}