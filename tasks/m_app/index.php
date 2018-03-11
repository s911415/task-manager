<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no;user-scalable=0;" />
	<title>工作管理系統</title>
	<link rel="stylesheet" href="media/css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="media/css/mobile.css"/>
	
	<script type="text/javascript" src="media/js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="media/js/jquery-ui-1.11.2.js"></script>
	<script type="text/javascript" src="media/js/func.js"></script>
	<script type="text/javascript" src="media/js/mobile.js"></script>
	<script type="text/javascript" src="<?=mkLink('User','js_list')?>&sp=1"></script>
</head>
<body>

<div id="mainC">
	<div id="mainContent">
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
	
	<footer>
		<div class="w">
			
		</div>
	</footer>
	
	<header>
		<div class="w">
			<div id="menu_bt">
				<div class="m_bar"></div>
				<div class="m_bar"></div>
				<div class="m_bar"></div>
			</div>
			<h1 id="main_title">工作管理系統</h1>
			
			<div id="funcs">
			
			</div>
		</div>
	</header>
</div>

<?
if(isset($_SESSION['id'])){
?>
<nav id="menu" class="menu">
	<div class="w">
	<h3>
	Hi, <span uid="<?=$_SESSION['id']?>"><?=$_SESSION['name']?></span>
	</h3>
	
	<?=mkMenu(User::getMenu())?>
	<div class="row c">
		&nbsp;
		<br/>
		<button class="bt fill" onclick="logout()">登出</button>
	</div>
	<script>
	function logout(){
		Ajax('User','logout',null,function(d){
			if(d){
				msgbox('登出成功','success',function(){
					location.href='./';
				});
			}
		});
	}
	</script>
	</div>
</nav>
<?
}else{
	
}
?>
<footer class="mr">
	
</footer>
<div id="msgbox">
	<div class="w"></div>
</div>
<script>
initBindEvent();
</script>
<?
if(false && getVal($_GET,'ispop')==1){
?>
<style>
.menu,header{display:none}
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