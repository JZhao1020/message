<?php
return [
    // 邮件配置
//    'host' => 'smtp.exmail.qq.com',				//smtp服务器地址
//    'user' => '',			//发送账号
//    'pwd'  => '',						//发送密码
//    'name' => '',				//发送人昵称
//    'system' => '',				//发送人昵称
//    'port' => 465,                                                  //端口
//    'secure' => 'ssl',                                              //协议
//    'debug'=> false,								//开发模式

    // 谷歌邮箱
    'host' => 'email-smtp.eu-central-1.amazonaws.com',				//smtp服务器地址
    'user' => '',			                    //发送账号
    'pwd'  => '',		//发送密码
    'name' => '',				                            //发送人昵称
    'system' => '',                            //发送邮箱
    'port' => 587,                                                  //端口
    'secure' => 'tls',                                              //协议
    'debug'=> false,								                //开发模式
];
