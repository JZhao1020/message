<?php


namespace message\driver;


use PHPMailer\PHPMailer\PHPMailer;
use Yurun\Util\Chinese;

class EmailSend{
    private $log;
    private $lang;
    // 邮件配置
    private static $host = '';
    private static $user = '';
    private static $pwd = '';
    private static $name = '';
    private static $system = '';
    private static $debug = '';

    /**
     * EmailSend constructor.
     * @param array $config
     * @param string $lang
     * @param $log
     */
    public function __construct($config = [], $lang = 'cn', $log){
        $this->log = $log;
        $this->lang = $lang;

        self::$host = $config['host'];
        self::$user = $config['user'];
        self::$pwd = $config['pwd'];
        self::$name = $config['name'];
        self::$system = $config['system'];
        self::$debug = $config['debug'];
    }

    /**
     * 发送邮件验证码
     * @param $email
     * @return int|string
     */
    public function sendCode($email){
        $mail = new PHPMailer();
        $code = randCode();
        //运营模式
        if(!self::$debug){
            try{
                //邮件调试模式
                // $mail->SMTPDebug = 2;
                //设置邮件使用SMTP
                $mail->isSMTP();
                $mail->Host = self::$host;

                // 启用SMTP验证
                $mail->SMTPAuth = true;
                $mail->SMTPSecure 	= 'ssl';
                $mail->Port 		= 465;

                $FromName 			= self::$name;			    		//发送人昵称
                $mail->WordWrap 	= 50;								//50个字符自动换行
                $mail->CharSet		= 'utf-8';						 	// 设置邮件内容的编码

                $mail->Username 	= self::$user;
                $mail->Password 	= self::$pwd;
                //设置发件人
                $mail->setFrom(self::$user , $FromName);
                //  添加收件人
                $mail->addAddress($email);
                // 将电子邮件格式设置为HTML
                $mail->isHTML(true);
                $mail->Subject = self::$name . '-验证码';
                if($this->lang == 'en'){
                    $mail->Body = self::htmbodyen($email, $code);
                }else if ($this->lang == 'hk'){
                    $mail->Body = Chinese::toTraditional(self::htmbody($email, $code))[0];
                }else{
                    $mail->Body = self::htmbody($email, $code);
                }

                //发送失败!
                if(!$mail->send()){
                    return 'Mailer Error: ' . $mail->ErrorInfo;
                }

                return $code;
            }catch (Exception $e){
                return 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }else{
            //开发模式不调用发送,结果都返回成功!
            return $code;
        }
    }

    /**
     * 发送邮件内容
     * @param $email
     * @param $key
     * @param array $params
     * @return array|bool|int|mixed|string|string[]
     */
    public function sendText($email, $key, $params = []){
        $body = getMessage('email', $key, $this->lang, $params);
        if(!self::$debug){
            $mail = new PHPMailer();
            try{
                //邮件调试模式
                // $mail->SMTPDebug = 2;
                //设置邮件使用SMTP
                $mail->isSMTP();
                $mail->Host = self::$host;

                // 启用SMTP验证
                $mail->SMTPAuth = true;
                $mail->SMTPSecure 	= 'ssl';
                $mail->Port 		= 465;

                $FromName 			= self::$name;			    		//发送人昵称
                $mail->WordWrap 	= 50;								//50个字符自动换行
                $mail->CharSet		= 'utf-8';						 	// 设置邮件内容的编码

                $mail->Username 	= self::$user;
                $mail->Password 	= self::$pwd;
                //设置发件人
                $mail->setFrom(self::$user , $FromName);
                //  添加收件人
                $mail->addAddress($email);
                // 将电子邮件格式设置为HTML
                $mail->isSMTP();
                $mail->Subject = self::$name;
                $mail->Body = $body;

                //发送失败!
                if(!$mail->send()){
                    return 'Mailer Error: ' . $mail->ErrorInfo;
                }
                return true;

            }catch (Exception $e){
                return 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }else{

            //开发模式不调用发送,结果都返回成功!
            return $body;
        }
    }

    //中文版
    private function htmbody($mailbox, $code)
    {
        $body = '<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" style=" background: #EEEEEE; font:14px/20px \'Helvetica\', Arial, sans-serif;">
    <tbody>
    <tr>
        <td style="padding: 30px 0;">
            <table cellspacing="0" cellpadding="0" width="650" align="center" border="0" style="background: #FFFFFF; border-radius: 10px">
                <tbody>
                <tr>
                    <td>
                        <table width="100%" style="background:#018CD6; border-radius: 10px 10px 0 0">
                            <tbody>
                            <tr>
                                <td width="1%" style=" padding: 10px 0px"></td>
                                <td style="font-size:24px; color:#FFF; padding:10px;">完成验证</td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td style="border-top:none; padding:30px; color: #000 ">

                        <table>
                            <tbody>
                            <tr>
                                <td>
                                   	 您好，欢迎您使用'.self::$name.'，我们竭诚为您服务。
                                    <p>下面是您的验证码信息：</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-top:1px solid #EEE; border-bottom: 1px solid #EEE; padding: 10px; color: #000">
                                    <strong>账户：</strong>
                                    <a href="mailto:'.$mailbox.'" target="_blank" style="color:#3D5E86;">'.$mailbox.'</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;">
                                    <p>您好!感谢您使用'.self::$name.'服务，您正在进行邮箱验证，请在验证码输入此验证码：<span style=\'color: #3D5E86\'>'.$code.'</span>，以完成验证。</p>
                                </td>
                            </tr>

                            <tr>
                                <td style="color: ##3D5E86;">
                                    该验证码将在15分钟后失效，请尽快完成操作。
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    - '.self::$name.'团队
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td style="padding:0 30px 20px; font-size: 12px; color: #999">
                        此邮件由系统自动发出，请勿直接回复。 有问题请<a href="mailto:'.self::$system.'" target="_blank">联系我们</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    </tbody>
</table>
';
        return $body;
    }

    //英文版
    private function htmbodyen($mailbox, $code)
    {
        $body = '<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" style=" background: #EEEEEE; font:14px/20px \'Helvetica\', Arial, sans-serif;">
    <tbody>
    <tr>
        <td style="padding: 30px 0;">
            <table cellspacing="0" cellpadding="0" width="650" align="center" border="0" style="background: #FFFFFF; border-radius: 10px">
                <tbody>
                <tr>
                    <td>
                        <table width="100%" style="background:#018CD6; border-radius: 10px 10px 0 0">
                            <tbody>
                            <tr>
                                <td width="1%" style=" padding: 10px 0px"></td>
                                <td style="font-size:24px; color:#FFF; padding:10px;">Complete the verification</td>
                            </tr>
                            </tbody>
                        </table>
    	
                    </td>
                </tr>
                <tr>
                    <td style="border-top:none; padding:30px; color: #000 ">
    	
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                   	 Hi, welcome to '.self::$name.', we do our best to serve you well.
                                    <p>Your information is the following:</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-top:1px solid #EEE; border-bottom: 1px solid #EEE; padding: 10px; color: #000">
                                    <a href="mailto:'.$mailbox.'" target="_blank" style="color:#3D5E86;">'.$mailbox.'</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px;">
                                    <p>Hi! Welcome to use the '.self::$name.' Platform, you are completing the email verification, please enter the following verification code:<span style=\'color: #3D5E86\'>'.$code.'</span>,to complete the verification process.</p>
                                </td>
                            </tr>
    	
                            <tr>
                                <td style="color: ##3D5E86;">
                                    This verification code will be expired in 15 minutes, please complete this as soon as possible.
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    - '.self::$name.'
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td style="padding:0 30px 20px; font-size: 12px; color: #999">
                       This is an automatic email, please do not reply. If you have any questions or concerns, please <a href="mailto:'.self::$system.'" target="_blank">contact us</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    </tbody>
</table>
';
        return $body;

    }
}