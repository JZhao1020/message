<?php
/**
 * @user JZ浩
 * @time 2020/10-27
 */


/**
 * 判斷是否中國號碼
 */
function isForeignPhone($phone){
    //判断使用国内/国际模板
    $pattern = '/^00.\d*$/';
    $res = preg_match($pattern,$phone);
    return $res;
}

/**
 * 随机获取验证码
 */
function randCode(){
    return mt_rand(102300, 999999);
}

/**
 * 解析获取模板内容
 * @param string $module 模块，email、sms
 * @param string $key 模板key
 * @param string $lang
 * @param array $params
 * @return array|bool|int|mixed|string|string[]
 */
function getTempMessage($module, $key, $lang, $params = []){
    try {
        //获取配置文件
        $file = dirname(__FILE__) . "//config//ContentConfig.php";
        $message = include $file;
        $body_info = $message[$module][$key];
        switch ($lang){
            case 'cn';
                $is_key ? $name = $is_key . '_cn' : $name = 'cn';
                $body = $body_info[$name];
                break;
            case 'en':
                $is_key ? $name = $is_key . '_en' : $name = 'en';
                $body = $body_info[$name];
                break;
            default:
                $is_key ? $name = $is_key . '_cn' : $name = 'cn';
                $body = $body_info[$name];
                break;
        }

        //正则获取模板参数
        $pattern = '/(?<=\{\$).*?(?=\})/';
        $match = preg_match_all($pattern,$body,$result);
        if ($match!==0){
            //判断参数是否齐全
            $is_param = isCheckMessageParam($result[0],$params);
            if ($is_param !== true)
                return $is_param;

            //替换模板字符串
            foreach ($result[0] as $k=>$v){
                $str_body = str_replace('{$'.$v.'}',$params[$v],$body);
                ($str_body!=='') ? $body = $str_body : '';
            }
        }
        return $body;

    }catch (Exception $exception){
        return 404;
    }
}

/**
 * 获取title-text结构的数据
 * @param $module
 * @param $key
 * @param $lang
 * @param array $params
 * @return array|bool|int
 */
function getJpushMessage($module, $key, $lang, $params = []){
    try {
        //获取配置文件
        $file = dirname(__FILE__) . "//config//ContentConfig.php";
        $message = include $file;
        $body_info = $message[$module][$key];
        switch ($lang){
            case 'cn';
                $title = $body_info['title_cn'];
                $body = $body_info['text_cn'];
                break;
            case 'en':
                $title = $body_info['title_en'];
                $body = $body_info['text_en'];
                break;
            default:
                $title = $body_info['title_cn'];
                $body = $body_info['text_cn'];
                break;
        }

        //正则获取模板参数
        $pattern = '/(?<=\{\$).*?(?=\})/';

        $match_title = preg_match_all($pattern,$title,$result);
        if ($match_title !== 0){
            //判断参数是否齐全
            $is_param = isCheckMessageParam($result[0], $params);
            if ($is_param !== true)
                return $is_param;

            //替换模板字符串
            foreach ($result[0] as $k=>$v){
                $str_title = str_replace('{$'.$v.'}', $params[$v], $title);
                ($str_title!=='') ? $title = $str_title : '';
            }
        }

        $match_body = preg_match_all($pattern,$body,$result);
        if ($match_body !== 0){
            //判断参数是否齐全
            $is_param = isCheckMessageParam($result[0], $params);
            if ($is_param !== true)
                return $is_param;

            //替换模板字符串
            foreach ($result[0] as $k=>$v){
                $str_body = str_replace('{$'.$v.'}', $params[$v], $body);
                ($str_body!=='') ? $body = $str_body : '';
            }
        }

        return ['title' => $title, 'body' => $body];

    }catch (Exception $exception){
        return 404;
    }
}

/**
 * 检查参数是否齐全
 */
function isCheckMessageParam($need_params,$params){
    foreach ($need_params as $k=>$v){
        if (!isset($params[$v])){
            return ['need_param'=>$v];
        }
    }
    return true;
}

function return_msg($status,$msg='',$data=[]){
    return ['status'=>$status,'msg'=>$msg,'data'=>$data];
}