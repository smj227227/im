$(function() {
	$('form').submit(function() {
		if($("input[name='name']").val() == '' || $("input[name='pass']").val() == '' || $("input[name='conpass']").val() == '' || $("input[name='vcode']").val() == '' || $("input[name='pcode']").val() == '') {
			$(".errortext").html('请填写完整信息');
			return false;
		}

		if(!telRuleCheck($("input[name='name']").val())) {
			$("input[name='name']").val('');
			$(".errortext").html('请输入正确的电话号码');
			return false;
		}

		if(!checkpass($("input[name='pass']").val())) {
			$("input[name='pass']").val('');
			$(".errortext").html('密码必须是6-8位数字,字母或下划线');
			return false;
		}

		var passstr = $("input[name='pass']").val();
		var passstr2 = $("input[name='conpass']").val();

		if(passstr != passstr2) {
			$(".errortext").html('两次密码输入不一致');
			return false;
		}

		var oValue = $("input[name='vcode']").val().toUpperCase();
		if(oValue != code) {
			$("input[name='vcode']").val('');
			$(".errortext").html('验证码不正确，请重新输入');
			return false;
		}

		var phonecodeValue = $("input[name='pcode']").val();
		if(!phoneCodePwd(phonecodeValue)){
			$("input[name='pcode']").val('');
			$(".errortext").html('短信验证码必须是4位数字');
			return false;
		}
	})

})

function telRuleCheck(string) {
	var pattern = /^1[34578]\d{9}$/;
	if(pattern.test(string)) {
		return true;
	}
	return false;
};

function checkpass(string) {
	var regstr = /^\w{6,8}$/;
	if(regstr.test(string)) {
		return true;
	}
	return false;
}

function phoneCodePwd(string){
	console.log(string);
//	debugger;
	var reg =/^\d{4}$/;      
	if(reg.test(string)){
		return true;
	}
	return false;
}

//验证码
var code;

function createCode() {
	code = ''
	var codeLength = 4;
	var codeV = $('#code');

	var random = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

	for(var i = 0; i < codeLength; i++) {
		var iIndex = Math.floor(Math.random() * 36);
		code += random[iIndex];
	}
	codeV.val(code);
}

window.onload = function() {
	createCode()
}

//短信验证
var validcode = true;
var pvcodevalue;

function validCode() {
	var time = 60;
	var phone = $('#name').val();
	var code = $('#phonecode');
	if(phone == ''){
		return false;
	}
	if(validcode) {
		console.log(phone);
		pvcodevalue='';
		$.ajax({
			type: "post",
			url: "/user/code",
			dataType:'json',
			data:{phone:phone},
			success: function(res) {
				console.log(res);
				if(res.code == 200) {
					pvcodevalue = res.phonecode;
				}
			}
		});
		validcode = false;
		var t = setInterval(function() {
			time--;
			code.val(time + '秒');
			if(time == 0) {
				clearInterval(t);
				code.val('重新获取');
				validcode = true;
			}
		}, 1000)
	}
}