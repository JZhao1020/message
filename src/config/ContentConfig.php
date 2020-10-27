<?php
/**
 * 消息模板
 */

return [
    // 短信模板
    'sms' => [

    ],
    // 邮件模板
    'email' => [
        // 验证码

        //注册成功
        'register_success'=>[
            'register_success_cn'=>'注册成功！',
            'register_success_en'=>'register success.',
        ],
        //投资成功
        'invest_success'=>[
            'invest_success_cn'=>'恭喜您，成功投资{$product_name}，详情请查看首页的“投资记录”列表中查看。',
            'invest_success_en'=>'Congratulations! You invested {$product_name} successfully. Please click here to check in investment record.',
        ],
    ],
];