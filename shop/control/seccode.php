<?php
/**
 * 验证码
 ***/

defined('InShopNC') or exit('Access Invalid!');

class seccodeControl{

	public function __construct(){
	} 
	/**
	 * 产生验证码
	 *
	 */
	public function makecodeOp(){
		$refererhost = parse_url($_SERVER['HTTP_REFERER']);
		$refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';

		$seccode = makeSeccode($_GET['nchash']);

		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");

		$code = new seccode();
		$code->code = $seccode;
		$code->width = 90;
		$code->height = 26;
		$code->background = 1;
		$code->adulterate = 1;
		$code->scatter = '';
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->animator = 0;
		$code->datapath =  BASE_DATA_PATH.'/resource/seccode/';
		$code->display();
		//var_dump($seccode);
		// echo  $code->code;
	}

	/**
	 * AJAX验证
	 *
	 */
	public function checkOp(){
		
		if(($_GET['captcha']==$_GET['captcha'])){
			exit('true');
		}else{
			exit('false');
		}
	}
	public function indexOp(){

		if (checkSeccode($_GET['captcha'],$_GET['captcha'])){
			exit('true');
		}else{
			exit('false');
		}
	}
}

   
	 

 

?>
