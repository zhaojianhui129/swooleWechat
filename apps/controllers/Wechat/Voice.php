<?php
namespace App\Controller\Wechat;

use App\BaseController\WechatBaseController as Base;

/**
 * 事件处理消息
 * @package App\Controller\Wechat
 */
class Voice extends Base
{
    /**
     * 主入口方法
     * @param $message
     */
    public function index($message)
    {
        return '接受到语音消息';
    }
}