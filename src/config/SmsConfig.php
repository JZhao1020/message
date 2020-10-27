<?php
return[
    //true为打开debug（调试模式，不发送短信）,false为关闭debug（运营模式，发送短信）
    'debug' => true,
    //appKey
    "appKey" => "",
    //appSecret
    "appSecret" => "",
    //中文签名
    "signName_cn" => "",
    //英文签名
    "signName_en" => "",
    //繁体签名
    "signName_hk" => "",
    //国内模板
    "china"=>[
        //中文模板
        "cn" => "",
        //英文模板
        "en" => "",
        //繁体模板
        "hk" => "",
    ],
    //国外模板
    "foreign"=>[
        //中文模板
        "cn" => "",
        //英文模板
        "en" => "",
        //繁体模板
        "hk" => "",
    ],

    // true - 海外号码则使用
    'is_yunpian' => false,

    // 云片相关配置
    'yunpian' => [
        'app_id' => '',
    ],
];