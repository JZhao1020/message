# message
消息推送

## 开源地址
https://github.com/JZhao1020/message

##1.安装
```
composer require hao/message
```

##2.实例化
```
$message = new \message\Message();
```

##2.1 验证码发送
```
$message->gateway('email','cn')->sendCode('18816784184@163.com');
```

##2.2 内容推送
```
$message->gateway('email','cn')->sendText('18816784184@163.com', 'invest_success', ['product_name' => '定期产品']);
```

##3.可用类型
```
---------------------------
类型      -- 验证码  -- 内容
---------------------------
短信      -- 是     -- 否
---------------------------
邮件      -- 是     -- 是
---------------------------
极光      -- 否     -- 是
---------------------------

```
