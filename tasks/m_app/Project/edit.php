<?
	$id=intval($_GET['id']);
	$method='修改';
	if($id==-1){
		$method='新增';
	}
?>

<div class="w">
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

		<?=Inp('create_time','建立時間','<input type="text" class="datetime"/>')?>
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
			'專案成員<button id="addPart" type="button">修改成員</button>',
			'<input type="hidden"/>
			<div id="plist"></div>',
			false
		)?>
		
		<div class="row c">
			<button class="bt"><?=$method?></button>
			<button type="reset">重設</button>
			<a href="<?=mkLink('Project','manage')?>"><button>返回專案管理</button></a>
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
</div>
<script>
passVal(<?=json_encode(Project::getProject($id))?>);
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
	var data=getVal('id|name|partner|create_time|owner|pm|allow_add|category');
	Ajax('Project','edit',data,function(d){
		if(d){
			msgbox('操作完成','success',function(){
				location.href='<?=mkLink('Project','manage')?>';
			});
		}else{
			msgbox('操作失敗','error');
		}
	});
}
refP();
</script>