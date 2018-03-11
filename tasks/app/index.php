<?php
chkLogin();
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<title>工作管理系統</title>
	<link rel="stylesheet" href="media/css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="media/css/style.css"/>
	<link rel="stylesheet" href="media/css/filetype.css"/>
	<link rel="stylesheet" href="media/css/material-icons.css.css"/>
	<link rel="stylesheet" href="media/css/common.css"/>
	<link rel="stylesheet" href="media/css/menu.css"/>
	<link rel="stylesheet" href="media/css/taskList.css"/>
	
	<!--<script type="text/javascript" src="media/js/prefix.js"></script>-->
	<script type="text/javascript" src="media/js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="media/js/jquery-ui-1.11.2.js"></script>
	<script type="text/javascript" src="media/js/func.js"></script>
	<script type="text/javascript" src="<?=mkLink('User','js_list')?>&sp=1"></script>
	<link rel="stylesheet" href="media/css/mobile.css"/>
</head>
<body>
<header>
	<div class="w">
		<div class="c" id="titlettt" style="  z-index:1;position: absolute;width: 100%;">
			
		</div>
		<div class="left" style="height:3rem">
			<a href="./" id="logo"></a><!--  <span>(工作管理系統)</span> -->
		</div>
		
		<div class="right">
			<nav id="top_menu">
				<?if(Session::get('id')){
				?>
				<!--Hi, <?=Session::get('name')?>--><a href="<?=mkLink('User','logout')?>">Logout&nbsp;(登出)</a>
				<?
				}else{
				?>
				<a href="<?=mkLink('User','login')?>">Login</a>
				<?
				}?>
				<div class="mobmenu"></div>
			</nav>
		</div>
	</div>
</header>

<nav id="menu">
	<div class="w">
		<div class="right" id="afgs">
		<?if(Session::get('id')){
			echo '<span class="un"> '.Session::get('name') . '</span><span class="logout"> (<a href="'.mkLink('User','logout').'">Logout</a>)</span>';
		}?>
		</div>
		<?=mkMenu(User::getMenu())?>
	</div>
</nav>
<nav id="fake_menu"></nav>
<script>
if($('#menu li').length==0){
	$('#menu,#fake_menu').hide();
	$('header').addClass('sh');
}
</script>
<div id="mainContent">
	<div class="w">
	<?
		$incPath='';
		if(empty($func)){
			$incPath=APP_PATH.$act.'.php';
		}else{
			$incPath=APP_PATH.$act.'/'.$func.'.php';
		}
		echo "\n<!--\n$incPath\n-->\n";
		include_once($incPath);
	?>
	</div>
</div>
<footer class="mr">
	
</footer>
<div id="msgbox">
	<div class="w"></div>
</div>
<script>

function updateInfo(){
	$('\
	<div>\
		\
		<div class="row">\
			<label for="acc">帳號</label>\
			<input type="text" id="acc" readonly value="<?=e(Session::get('account'))?>"/>\
		</div>\
		<div class="row">\
			<label for="name">姓名</label>\
			<input type="text" id="name" readonly value="<?=e(Session::get('name'))?>"/>\
		</div>\
		<div class="row required">\
			<label for="oldpwd">舊密碼</label>\
			<input type="password" id="oldpwd" required/>\
		</div>\
		<div class="row">\
			<label for="password">新密碼</label>\
			<input type="password" id="password" placeholder="空白為不變"/>\
		</div>\
		<div class="row">\
			<label for="newpwd2">確認新密碼</label>\
			<input type="password" id="newpwd2" placeholder=""/>\
		</div>\
		<div class="row">\
			<label for="email">Email</label>\
			<input type="email" id="email" placeholder="請輸入業務用Email" value="<?=e(Session::get('email'))?>"/>\
		</div>\
	</div>\
	').dialog({
		modal:	true,
		close:	function(){
			if(XHR){
				XHR.abort();
			}
			$(this).remove();
		},
		buttons:	{
			'確定':	function(){
				var t=$(this);
				var data=getVal('oldpwd|password|newpwd2|email');
				if(data.password!=data.newpwd2){
					msgbox('確認新密碼不相同', 'error');
					return;
				}
				Ajax('User', 'updateInfo', data, function(d){
					if(typeof d == "string"){
						msgbox(d, "error");
						return;
					}
					if(d){
						if(data.password!='' && data.password!=data.oldpwd){
							new Image().src="<?=mkLink('User', 'logout')?>";
							msgbox('已套用新密碼，請重新登入。', 'success', function(){
								location.href='<?=mkLink('User', 'login')?>';
							});
						}
					}else{
						msgbox("發生未知錯誤", 'error');
					}
					$(this).dialog('close');
				});
			},
			'取消':	function(){
				$(this).dialog('close');
			}
		},
		title:	'更改使用者資訊',
		width:	500,
		resizable:	false,
	});

}
</script>
<script>
initBindEvent();
</script>
<?
if(getVal($_GET,'ispop')==1){
?>
<style>
#menu,header{display:none}
#mainContent{
	float:none;
	width:96%;
	margin:0 auto;
}
</style>
<?
}
?>
</body>
</html>