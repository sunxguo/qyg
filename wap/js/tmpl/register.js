$(function(){
	
	
	$.sValid.init({//注册验证
        rules:{
        	username:"required",
            userpwd:"required",            
            password_confirm:"required",
            code:"required"
           
        },
        messages:{
            username:"用户名必须填写！",
            userpwd:"密码必填!", 
            password_confirm:"确认密码必填!",
            code:"验证码必填"
           
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $(".error-tips").html(errorHtml).show();
            }else{
                $(".error-tips").html("").hide();
            }
        }  
    });

	$('#loginbtn').click(function(){	
		if(!(/^1[34578]\d{9}$/.test($("#user_name").val())))
	    {
	        alert("请输入正确的手机号");
	    }
	     // if($("#code").val() == '')
	     // {
	     // 	alert('验证码必填');
	     // }
		var username =$("#user_name").val();
		var pwd = $("#userpwd").val();
		var password_confirm = $("#password_confirm").val();
		var client = $("#client").val();
		var code = $("#code").val();
		if($.sValid()){
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=login&op=register",	
				data:{username:username,password:pwd,password_confirm:password_confirm,code:code,client:client},
				dataType:'json',
				success:function(result){
					//alert(result);
					if(!result.datas.error){
						if(typeof(result.datas.key)=='undefined'){
							return false;
						}else{
							addcookie('username',result.datas.username);
							addcookie('key',result.datas.key);
							location.href = WapSiteUrl+'/tmpl/member/member.html';
						}
						$(".error-tips").hide();
					}else{
						$(".error-tips").html(result.datas.error).show();
					}
				}
			});			
		}
	});
});