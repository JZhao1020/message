<?php
/**
 * 消息模板
 */

return [
    // 短信模板
    'sms' => [
        // 海外短信验证码模板
        'sms_code' => [
            'en' => '{$signature}: Dear user, your verification code is: {$rand} Code is valid for 15 minutes.'
        ],
    ],
    // 邮件模板
    'email' => [
        // 验证码

        //注册成功
        'register_success'=>[
            'cn'=>'注册成功！',
            'en'=>'register success.',
        ],
        //投资成功
        'invest_success'=>[
            'cn'=>'恭喜您，成功投资{$product_name}，详情请查看首页的“投资记录”列表中查看。',
            'en'=>'Congratulations! You invested {$product_name} successfully. Please click here to check in investment record.',
        ],
    ],
    // jpush模板
    'jpush' => [
        'register_success' => [
            'title_cn'=>'标题',
            'title_en'=>'title',
            'text_cn'=>'注册成功！',
            'text_en'=>'register success.',
        ],
        'invest_success' => [
            'title_cn'=>'{$title}',
            'title_en'=>'{$title}',
            'text_cn'=>'恭喜您，成功投资{$product_name}，详情请查看首页的“投资记录”列表中查看。',
            'text_en'=>'Congratulations! You invested {$product_name} successfully. Please click here to check in investment record.',
        ],
    ],
];