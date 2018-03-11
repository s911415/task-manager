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
	<link rel="stylesheet" href="media/css/common.css"/>
	<link rel="stylesheet" href="media/css/menu.css"/>
	<link rel="stylesheet" href="media/css/taskList.css"/>
	
	<script type="text/javascript" src="media/js/prefix.js"></script>
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