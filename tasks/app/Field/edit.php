<?
	$id=intval($_GET['id']);
	$method='修改';
	if($id==-1){
		$method='新增';
	}
?>
<h2 class="title">
	<?=$method?>欄位
</h2>
<style>
.flex.fix>.row:first-child{
	margin-left:0;
}
</style>
<form class="form">
	<div class="flex fix">
		<?=Inp('name','名稱','<input type="text"/>')?>
		<?
		$aa=[];
		foreach(CustomField::$types as $tt){
			$aa[]='<option>'.$tt.'</option>';
		}
		echo Inp('type','類型','<select class="txt">'.implode('',$aa).'</select>');
		?>
		<?=Inp('def','預設值','<input type="text"/>',false)?>
		<?=Inp('req','必填','<input type="checkbox"/>',false)?>
	</div>
	<style>
	#row_req{
		width:3rem;
		flex:none;
	}
	</style>
	<script>
	$('#type').change(function(){
		var s='radio|select|checkbox'.split('|');
		if(s.indexOf(this.value)!==-1){
			$('#fh').show();
		}else{
			$('#p_val').val('');
			$('#fh').hide();
		}
	});
	
	$(window).load(function(){
		$('#type').change();
	});
	</script>
	<div class="flex fix" id="fh">
		<?=Inp('p_val','可能的值(使用 | 分格)','<input type="text"/>',false)?>
	</div>
	<div class="row c">
		<button class="bt"><?=$method?></button>
		<a href="<?=mkLink('Field','manage')?>"><button>返回自訂欄位列表</button></a>
	</div>
	
	<div class="hide">
		<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
	</div>
</form>
<script>
passVal(<?=json_encode(CustomField::getInfo($id))?>);
</script>
<script>

function fcb(){
	var data=getVal('id|name|type|def|req|p_val');
	Ajax('CustomField','edit',data,function(d){
		if(d){
			msgbox('操作完成','success',function(){
				location.href='<?=mkLink('Field','manage')?>';
			});
		}else{
			msgbox('操作失敗','error');
		}
	});
}
</script>