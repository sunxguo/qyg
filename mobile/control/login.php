<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * 
 */

use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

    private function isQQLogin(){
        return !empty($_GET[type]);
    }
	/**
	 * 登录
	 */
	public function indexOp(){
        if(!$this->isQQLogin()){
            if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
                output_error('登录失败');
            }
        }
		$model_member = Model('member');
        $array = array();
        if($this->isQQLogin()){
            $array['member_qqopenid']	= $_SESSION['openid'];
        }else{
            $array['member_name']	= $_POST['username'];
            $array['member_passwd']	= md5($_POST['password']);
        }
        $member_info = $model_member->getMemberInfo($array);
        if(!empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token){
                    if($this->isQQLogin()){
                        setNc2Cookie('username',$member_info['member_name']);
                        setNc2Cookie('key',$token);
                        header("location:".WAP_SITE_URL.'/tmpl/member/member.html?act=member');
                    }else{
                        output_data(array('username' => $member_info['member_name'], 'key' => $token));
                    }
            } else {
                output_error('登录失败');
            }
        } else {
            output_error('用户名密码错误');
        }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        //$model_mb_user_token->delMbUserToken($condition);
        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $_POST['client'] == null ? 'Android' : $_POST['client'] ;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);
        if($result) {
            return $token;
        } else {
            return $token;
        }
    
    }

	/**
	 * 注册 重复注册验证 
	 */
	public function registerOp(){
		 if (process::islock('reg')){
			output_error('您的操作过于频繁，请稍后再试');
		} 
        if(!preg_match("/^1[34578]\d{9}$/",$_POST['username']))
        {
            output_error('请输入正确的手机号');exit;
        }
        if($_POST['code'] != $_SESSION['mcodes'])
        {
            output_error('验证码不正确');exit;
        }
		$model_member	= Model('member');
        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])){
	        process::addprocess('reg');
            $token = $this->_get_token($member_info['member_id'], $_POST['username'], $_POST['client']);
            if($token) {
                output_data(array('username' => $_POST['username'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }

    public function sendnoteOp()
        {
            $phone=$_POST['phone'];
            // $phone=$phonees['phone'];
            $mcode = ""; 
            for ($i = 0; $i < 6; $i++){
                $mcode .= rand(0, 9); 
            } 
            header("content-type:text/html; charset=utf-8;");
            session_start();//开启缓存
            if (isset($_SESSION['time']))//判断缓存时间
            {
                session_id();
                $_SESSION['time'];
            }
            else
            {
                $_SESSION['time'] = date("Y-m-d H:i:s");
            }
            unset($_SESSION["mcodes"]);
            $_SESSION['mcodes']=$mcode;//将content的值保存在session中
            $statusStr = array(
                    "0" => "短信发送成功",
                    "-1" => "参数不全",
                    "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
                    "30" => "密码错误",
                    "40" => "账号不存在",
                    "41" => "余额不足",
                    "42" => "帐户已过期",
                    "43" => "IP地址限制",
                    "50" => "内容含有敏感词"
                );  
            $smsapi = "http://www.smsbao.com/"; //短信网关
            $user = "quanyougou"; //短信平台帐号
            $pass = md5("quanyougou123"); //短信平台密码
            $content="【全优购】本次验证码为".$mcode;//要发送的短信内容
            // $phone =;
            $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
            $result =file_get_contents($sendurl);
            //return $_SESSION['mcode'];
        }
}
