<?
	$id=intval($_GET['id']);
	$method='修改';
	if($id==-1){
		$method='新增';
	}
?>
<h2 class="title">
	<?=$method?>專案
</h2>
<form class="form">
	<div class="two clear">
		<?=Inp('name','專案名稱','<input type="text"/>')?>
		<?=Inp('category','專案分類 (用'.Project::$split.'分隔多個分類)','<input type="text" list="allcat"/>',false)?>
	</div>
	
	<datalist id="allcat"></datalist>
	<script>
	Ajax('Project','getAllCategory',{},function(d){
		var ac=$("#allcat");
		d.forEach(function(s){
			ac.append('<option>'+s+'</option>');
		});
	});
	</script>

	<?=Inp('create_time','建立時間','<input type="text" class="datetime" value="'.date('Y-m-d H:i:s').'"/>')?>
	<?=Inp('pm','PM','<select class="txt"></select>')?>
	
	
	<script>
	appendGetToSelect('#pm',function(n){
		if(typeof n=="undefined") return USERS;
		return USERS[n];
	});
	</script>
	<?=Inp(
		'allow_add',
		'\允許成員新增工作',
		'<input type="checkbox"/>',
		false
	)?>
	<?=Inp(
		'done',
		'\結案',
		'<input type="checkbox"/>',
		false
	)?>
	<?=Inp(
		'partner',
		'專案成員&emsp;<button id="addPart" type="button">修改成員</button>',
		'<input type="hidden"/>
		<div id="plist"></div>',
		false
	)?>
	
	<?=Inp('cust_field','自訂欄位','<input type="hidden"/>')?>
	<table id="custf" class="table">
	<thead>
		<tr>
			<th width="20"></th>
			<th>欄位名稱</th>
			<th width="150">類型</th>
			<th width="50">排序</th>
		</tr>
	</thead>
	<tbody>
		<?
		foreach(
			CustomField::getList() as $r
		){
		?>
		<tr data-cfid="<?=$r->id?>" class="oifhd">
			<td>
				<input type="checkbox" value="<?=$r->id?>" class="cfid"/>
			</td>
			<td>
				<?=$r->name?>
			</td>
			<td>
				<?=$r->type?>
			</td>
			<td>
				<input type="number" value="0" class="sort" min="0"/>
			</td>
		</tr>
		<?
		}
		?>
	</tbody>
	</table>
	
	<div class="row c">
		<button class="bt"><?=$method?></button>
		<button type="reset">重新輸入</button>
		<a href="<?=mkLink('Project','manage')?>"><button>取消並返回上頁</button></a>
	</div>
	
	<script>
	$("#partner").change(refP);
	function refP(){
		var n=[];
		var d=$("#partner").val().split(',');
		d.forEach(function(id){
			n.push(USERS[id]);
		});
		
		var plist=$("#plist");
		if(n.length>0){
			plist.text(n.join('、'));
		}else{
			plist.text('沒有人');
		}
	}
	</script>
	
	<div class="hide">
		<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
		<?=Inp('owner','owner','<input type="hidden" value="'.$_SESSION['id'].'"/>')?>
	</div>
</form>
<script>
<?=$id==-1?0:1?> && passVal(<?=json_encode(Project::getProject($id))?>);
</script>
<script>
var cuf=$('#cust_field').val();
if(cuf){
	cuf=cuf.split(',');
	for(var i=0;i<cuf.length;i++){
		var fid=cuf[i];
		var tr=$('tr[data-cfid="'+fid+'"]');
		tr.find('.cfid').prop('checked',true);
		tr.find('.sort').val(i);
	}
}
</script>
<script>
function refCFID(){
	var ids=[];
	var trs=$('.oifhd');
	trs.each(function(){
		var t=$(this);
		var checked=t.find('.cfid').prop('checked');
		var sort=t.find('.sort').val();
		if(!checked) return;
		
		ids[sort*1]=t.data('cfid');
	});
	
	return ids;
}
</script>
<script>
$('#addPart').click(function(){
	var partner=$("#partner").val();
	window.open(
		'<?=mkLink('User','select',[
			'type'	=>	'checkbox',
			'target'=>	'partner',
			'ispop'	=>	1
		])?>#' + partner,
		'selectPartner',
		'width=640,height=300'
	).focus();
});

function fcb(){
	$('#cust_field').val(refCFID().join(','));
	var data=getVal('id|name|partner|create_time|owner|pm|allow_add|category|cust_field');
	Ajax('Project','edit',data,function(d){
		if(d){
			location.href='<?=mkLink('Project','manage')?>';
		}else{
			msgbox('操作失敗','error');
		}
	});
}
refP();
</script>