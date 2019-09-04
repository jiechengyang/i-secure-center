<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Yangjiecheng
 * Date: 2019/9/2 0002
 * Time: 下午 18:06
 */

namespace jcore\iSecureCenter\api\video;

use jcore\iSecureCenter\api\BaseApi;

class Cameras extends BaseApi
{
    protected $_uriMap = [
        'getPreviewURLs' => '/api/video/v1/cameras/previewURLs',// 获取监控点预览取流URL
        'getPlaybackURLs' => '/api/video/v1/cameras/playbackURLs',// 查询编码设备列表
        'searchTalkURLs' => '/api/video/v1/cameras/talkURLs',// 查询对讲URL
        'controlling' => '/api/video/v1/ptzs/controlling',// 根据监控点编号进行云台操作
        'manualCapture' => '/api/video/v1/manualCapture',// 手动抓图
        'selZoom' => '/api/video/v1/ptzs/selZoom',// 监控点3D放大
        'startRecord' => '/api/video/v1/manualRecord/start',// 开始手动录像
        'stopRecord' => 'api/video/v1/manualRecord/stop',// 停止手动录像
        'statusRecord' => '/api/video/v1/manualRecord/status',// 获取手动录像状态
        'addPresets' => '/api/video/v1/presets/addition',// 设置预置点信息
        'searchPresets' => '/api/video/v1/presets/searches',// 查询预置点信息
        'delPresets' => '/api/video/v1/presets/deletion',// 删除预置点信息
        'getPresets' => '/api/video/v1/presets/get',// 批量获取监控点的预置点信息
        'getEventsPicture' => '/api/video/v1/events/picture',// 获取视频事件的图片
    ];

    private function getCommand($cmd)
    {
        $commands = [
            'LEFT', // 左转
            'RIGHT', // 右转
            'UP', // 上转
            'DOWN', // 下转
            'ZOOM_IN', // 焦距变大
            'ZOOM_OUT', // 焦距变小
            'LEFT_UP', // 左上
            'LEFT_DOWN', // 左下
            'RIGHT_UP', // 右上
            'RIGHT_DOWN', // 右下
            'FOCUS_NEAR', // 焦点前移
            'FOCUS_FAR', // 焦点后移
            'IRIS_ENLARGE', // 光圈扩大
            'IRIS_REDUCE', // 光圈缩小
            'GOTO_PRESET', // 以下命令presetIndex不可 为空： GOTO_PRESET到预置点
        ];
        $cmd = strtoupper($cmd);
        if (!in_array($cmd, $commands)) {
            return false;
        }

        return $cmd;
    }

    public function getPreviewURLs($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        !isset($data['streamType']) && $data['streamType'] = 1;
        !isset($data['transmode']) && $data['transmode'] = 1;
        empty($data['protocol']) && $data['protocol'] = 'rtsp';

        $this->setRequestBody($data);

        return true;
    }

    public function getPlaybackURLs($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

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

        $data['beginTime'] = date(DATE_ISO8601, strtotime($data['beginTime']));
        $data['endTime'] = date(DATE_ISO8601, strtotime($data['endTime']));
        !isset($data['streamType']) && $data['streamType'] = 1;
        !isset($data['transmode']) && $data['transmode'] = 1;
        empty($data['protocol']) && $data['protocol'] = 'rtsp';
        $this->setRequestBody($data);

        return true;
    }

    public function searchTalkURLs($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        !isset($data['transmode']) && $data['transmode'] = 1;
        $this->setRequestBody($data);

        return true;
    }

    public function controlling($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        if (!isset($data['action'])) {
            $this->setError('请选择开始【0】或停止【1】');
            return false;
        }

        if (empty($data['command'])) {
            $this->setError('请提供操作指令');
            return false;
        }

        if ($data['command'] = $this->getCommand(trim($data['command'])) === false) {
            $this->setError('请提供正确的操作指令');
            return false;
        }

        if ('GOTO_PRESET' === $data['command'] && empty($data['presetIndex'])) {
            $this->setError('GOTO_PRESET下预置点编号必须提供');
            return false;
        }

        !isset($data['speed']) && $data['speed'] = 50;
        $this->setRequestBody($data);

        return true;
    }

    public function manualCapture($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function selZoom($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        if (!isset($data['startX'])) {
            $this->setError('开始放大的X坐标必须提供');
            return false;
        }

        if (!isset($data['startY'])) {
            $this->setError('开始放大的Y坐标必须提供');
            return false;
        }

        if (!isset($data['endX'])) {
            $this->setError('结束放大的X坐标必须提供');
            return false;
        }

        if (!isset($data['endY'])) {
            $this->setError('结束放大的Y坐标必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function startRecord($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        !isset($data['recordType']) && $data['recordType'] = 6;
        !isset($data['type']) && $data['type'] = 0;

        $this->setRequestBody($data);

        return true;
    }

    public function stopRecord($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        if (empty($data['taskID'])) {
            $this->setError('手动录像编号必须提供');
            return false;
        }

        !isset($data['type']) && $data['type'] = 0;

        $this->setRequestBody($data);

        return true;
    }

    public function statusRecord($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        if (empty($data['taskID'])) {
            $this->setError('手动录像编号必须提供');
            return false;
        }

        !isset($data['type']) && $data['type'] = 0;

        $this->setRequestBody($data);

        return true;
    }

    public function addPresets($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        if (empty($data['presetName'])) {
            $this->setError('预置点名称必须提供');
            return false;
        }

        if (!isset($data['presetIndex'])) {
            $this->setError('预置点编号必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function searchPresets($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function delPresets($data)
    {
        if (empty($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function getPresets($data)
    {
        if (empty($data['cameraIndexCode']) || !is_array($data['cameraIndexCode'])) {
            $this->setError('监控点唯一标识集【至少提供一个点】必须提供');
            return false;
        }

        $this->setRequestBody($data);

        return true;
    }

    public function getEventsPicture($data)
    {
        if (empty($data['svrIndexCode'])) {
            $this->setError('图片存储服务器唯一标识必须提供');
            return false;
        }

        if (!isset($data['picUri'])) {
            $this->setError('图片的相对地址必须提供');
            return false;
        }

        empty($data['netProtocol']) && $data['netProtocol'] = 'https';

        $this->setRequestBody($data);

        return true;
    }
}