<?php


namespace message\driver;


use JPush\Client;
use Yurun\Util\Chinese;

class JpushSend{
    private static $cliend;
    private static $lang;

    public function __construct($config = [], $lang = 'cn', $log){
        $this->log = $log;
        self::$lang = $lang;

        $application = $config['application'];
        $arr = $config[$application];
        self::$cliend = new Client($arr['appKey'],$arr['MasterSecret']);
    }

    /**
     * 消息推送
     * @param string $address 极光设备id-单用户 / all-群发
     * @param string $key 消息模板key
     * @param array $params
     * @param string $platform
     * @param array $jpush_extra 极光推送额外参数
     * @return array|string
     */
    public function sendText($address, $key, $params = [], $platform='all', $jpush_extra = []){
        $arr = getJpushMessage('jpush', $key, self::$lang, $params);
        if (isset($arr['need_param']))
            return return_msg(500,'缺少消息模板参数'.$arr['need_param']);

        if(is_numeric($arr))
            return return_msg(404,'解析消息模板失败');

        //判断推送中文或英文
        if(self::$lang == 'hk'){
            $arr['title'] = Chinese::toTraditional($arr['title'])[0];
            $arr['body'] = Chinese::toTraditional($arr['body'])[0];
        }

        try{
            if ($address == 'all'){
                $res = self::$cliend->push()
                    ->setPlatform($platform)
                    ->addAllAudience()
                    ->setNotificationAlert($arr['body'])
                    ->send();
            }else{
                $res = self::$cliend->push()
                    ->setPlatform($platform)
                    ->addAlias($address)
                    ->setMessage($arr['body'], $arr['title'])
                    ->setOptions(null,null,null,null,null)
                    ->addAndroidNotification($arr['body'], $arr['title'],null, $jpush_extra)
                    ->addIosNotification($arr['body'],null,'+1',true,null, $jpush_extra)
                    ->send();
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
        return $res;
    }
}