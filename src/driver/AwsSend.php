<?php


namespace message\driver;

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class AwsSend{
    private static $lang;
    private static $debug;
    private static $config = '';

    public function __construct($config = [], $lang = 'cn', $log)
    {
        self::$lang = $lang;
        self::$config = $config;
        self::$debug = $config['debug'];
    }

    /**
     * 短信验证码
     * @param $phone
     * @return int|mixed|\SimpleXMLElement
     */
    public function sendCode($phone){
        try {
            // 短信中的替换变量json字符串
            $rand = randCode();
            if (self::$debug)
                return $rand;

            $params = array(
                'credentials' => array(
                    'key' => self::$config['key'],
                    'secret' => self::$config['secret'],
                ),
                'region' => self::$config['region'],
                'version' => self::$config['version'],
            );

            $sns = new \Aws\Sns\SnsClient($params);
            $message = getTempMessage('aws', 'sms_code', self::$lang, ['rand' => $rand]);

            $args = array(
                "SMSType" => "Transactional",
                //短信内容
                "Message" => $message,
                //手机号,包括前缀
                "PhoneNumber" => $phone
            );


            $result = $sns->publish($args);
            $result = $result->toArray();

            // 发送成功
            if (isset($result['@metadata']) && isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == 200) {
                return $rand;
            }

            return false;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}