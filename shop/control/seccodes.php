<?php
/**
 * 验证码
 ***/
    header("content-type:text/html; charset=utf-8;");
    
    vCodeOp();
	function vCodeOp() {
	
	session_start();
	if (isset($_SESSION['time']))//判断缓存时间
    {
        session_id();
        $_SESSION['time'];
    }
    else
    {
        $_SESSION['time'] = date("Y-m-d H:i:s");
    }
	$num = 4; $size = 20; $width = 90; $height = 25;
    !$width && $width = $num * $size * 4 / 5 + 5;
    !$height && $height = $size + 10; 
    // 去掉了 0 1 O l 等
    $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
    $code = '';
    $stres='';
    for ($i = 0; $i < $num; $i++) {
        $stres .= $str[mt_rand(0, strlen($str)-1)];
    } 
    $code=$stres;
    

     // 画图像
    $im = imagecreatetruecolor($width, $height); 
    // 定义要用到的颜色
    $back_color = imagecolorallocate($im, 235, 236, 237);
    $boer_color = imagecolorallocate($im, 118, 151, 199);
    $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120)); 
    // 画背景
    imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
    // 画边框
    imagerectangle($im, 0, 0, $width-1, $height-1, $boer_color); 
    // 画干扰线
    for($i = 0;$i < 5;$i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
    } 
    // 画干扰点
    for($i = 0;$i < 50;$i++) {
        $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
    } 
    // 画验证码
    // @imagefttext($im, $size , 0, 5, $size + 3, $text_color, 'c:\\WINDOWS\\Fonts\\simsun.ttc', $code);
    @imagefttext($im, $size , 0, 5, $size + 3, $text_color, '../msyh.ttf', $code); 
    
    // header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
    header("Content-type: image/png;charset=gb2312");
    imagepng($im);
    imagedestroy($im);
    $_SESSION['abc']=$code;
   
}

 
	 
    //生成验证码
 //    public function createcodeOp()
 //    {

 //    session_start();
 //    // $srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnm";
 //    $srcstr = "123456";
 //    for ($i = 0; $i < 4; $i++){
	// 	        $str .= rand(0, 9); 
	// 	} 
    
	// //随机生成的字符串
	
	// $_SESSION["verification"] = $str;
	// //验证码图片的宽度
	// $width  = 90;     
	 
	// //验证码图片的高度
	// $height = 25;    
	 
	// //声明需要创建的图层的图片格式
	// @ header("Content-Type:image/png");
	 
	// //创建一个图层
	// $im = imagecreate($width, $height);
	 
	// //背景色
	// $back = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
	 
	// //模糊点颜色
	// $pix  = imagecolorallocate($im, 187, 230, 247);
	 
	// //字体色
	// $font = imagecolorallocate($im, 41, 163, 238);
	 
	// //绘模糊作用的点
	// mt_srand();
	 
	// //输出字符
	// imagestring($im, 5, 7, 5, $str, $font);
	 
	// //输出矩形
	// imagerectangle($im, 0, 0, $width -1, $height -1, $font);
	 
	// //输出图片
	// imagepng($im);
	 
	// imagedestroy($im);

	
	 
	// //选择 cookie
	// //SetCookie("verification", $str, time() + 7200, "/");
	 
	// //选择 Session
	
 // }

 
 //4个数字，显示大小为15
 

?>
