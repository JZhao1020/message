<?php


namespace message\driver;


use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use Yunpian\Sdk\YunpianClient;

class SmsSend{
    private static $lang;
    private static $ali = '';
    private static $debug = '';
    private static $appKey = '';
    private static $appSecret = '';
    private static $signName_cn = '';
    private static $signName_en = '';
    private static $signName_hk = '';
    private static $china = '';
    private static $foreign = '';

    public function __construct($config = [], $lang = 'cn', $log)
    {
        self::$lang = $lang;
        self::$debug = $config['debug'];
        self::$appKey = $config['appKey'];
        self::$appSecret = $config['appSecret'];
        self::$signName_cn = $config['signName_cn'];
        self::$signName_en = $config['signName_en'];
        self::$signName_hk = $config['signName_hk'];
        self::$china = $config['china'];
        self::$foreign = $config['foreign'];
        self::$ali = $config;
    }

    /**
     * 短信验证码
     * @param $phone
     * @return int|mixed|\SimpleXMLElement
     */
    public function sendCode($phone){
        $res = isForeignPhone($phone);
        ($res == 1) ? $nation = self::$foreign : $nation = self::$china;
        //判断使用对应语言的变量
        switch (self::$lang){
            case 'cn';
                $signName = self::$signName_cn;
                $template_code = $nation['cn'];
                break;
            case 'hk':
                $signName = self::$signName_hk;
                $template_code = $nation['hk'];
                break;
            case 'en':
                $signName = self::$signName_en;
                $template_code = $nation['en'];
                break;
        }

        // 短信中的替换变量json字符串
        $rand = randCode();
        if (self::$debug)
            return $rand;

        $code_json = json_encode(['code'=>$rand]);

        //如果是国外的号码，改用云片发送
        if ($res == 1 && self::$ali['is_yunpian'] === true){
            //00转换为+
            $sub_phone = substr($phone,0,2);
            if ($sub_phone=='00'){
                $phone = '+'.substr($phone,2,strlen($phone));
            }

            $client = YunpianClient::create(self::$ali['yunpian']['app_id']);
            $param = [YunpianClient::MOBILE => $phone, YunpianClient::TEXT => getTempMessage('sms','sms_code','en',['signature' => self::$signName_en, 'rand' => $rand])];
            $send_result = $client->sms()->single_send($param);
            $code = $send_result->data()['code'];
            if ($code==0){
                return $rand;
            }
            return $send_result->data()['msg'];
        }

        //初始化阿里云config
        Config::load();

        //初始化用户profile实例
        $profile = DefaultProfile::getProfile("cn-hangzhou",self::$appKey,self::$appSecret);
        DefaultProfile::addEndpoint("cn-hangzhou",'cn-hangzhou','Dysmsapi','dysmsapi.aliyuncs.com');
        $acsClient = new DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 设置短信接收号码
        $request->setPhoneNumbers($phone);
        // 设置签名名称
        $request->setSignName($signName);
        // 设置模板CODE
        $request->setTemplateCode($template_code);

        // 可选，设置模板参数
        if(!empty($code_json)) {
            $request->setTemplateParam($code_json);
        }

        // 发起请求
        $acsResponse =  $acsClient->getAcsResponse($request);

        // 默认返回stdClass，通过返回值的Code属性来判断发送成功与否
        if($acsResponse && strtolower($acsResponse->Code) == 'ok')
        {
            return $rand;
        }
        return $acsResponse;
    }

    public function sendText($key){

    }
}