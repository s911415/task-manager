<?
	$id=intval($_GET['id']);
	$method='修改';
	if($id==-1){
		$method='新增';
	}
?>
<h2 class="title">
	<?=$method?>使用者
</h2>
<form class="form">
	<div class="two fix">
		<?=Inp('account','帳號','<input type="text"/>')?>
		<?=Inp('name','名稱','<input type="text"/>')?>
	</div>
	
	<div class="two fix">
		<?=Inp('password','密碼','<input type="password" class="txt"/>')?>
		<?=Inp('admin','權限','<select class="txt" def="0"></select>')?>
	</div>
	<script>appendGetToSelect('#admin',getAdmin)</script>

	<div class="row c">
		<button class="bt"><?=$method?></button>
		<a href="<?=mkLink('User','manage')?>"><button>返回使用者管理</button></a>
	</div>
	
	<div class="hide">
		<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
	</div>
</form>
<script>
passVal(<?=json_encode(User::getUser($id))?>);
</script>
<script>

function fcb(){
	var data=getVal('id|account|password|admin|name');
	Ajax('User','edit',data,function(d){
		if(d){
			msgbox('操作完成','success',function(){
				location.href='<?=mkLink('User','manage')?>';
			});
		}else{
			msgbox('操作失敗','error');
		}
	});
}
</script>