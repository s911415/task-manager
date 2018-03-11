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

<style>
#cat_span input{
	display:inline-block;
	width:8rem;
	margin-right:.5rem;
}
.edit{
}
</style>

<form class="form">
	<div class="two clear">
		<?=Inp('name','專案名稱','<input type="text"/>')?>
		<?=Inp('cat_span','|專案分類','<span > </span>',false)?>
		
	</div>
	
	<datalist id="allcat"></datalist>
	<script>
/*	Ajax('Project','getAllCategory',{},function(d){
		var ac=$("#allcat");
		d.forEach(function(s){
			ac.append('<option>'+s+'</option>');
		});
	});
*/
Ajax('Project','getAllCategory',null,function(d){
	var childs=d.child;
	var span=$('#cat_span');
	var catTree={};
	function makeSelect(par,child,deep){
		var se=span.find('datalist');
		var inp=span.find('input.txt');
		var maxDeep=inp.length;
		for(var i=deep;i<maxDeep;i++){
			se.eq(i).remove();
			inp.eq(i).remove();
		}
		
		var iid='deep_'+deep;
		var select=$('<datalist></datalist>');
		var inp=$('<input type="text" class="txt" list="deep_'+deep+'" name="cat[]"/>');
		inp.attr('list',iid).attr('deep',deep);
		select.attr('id',iid);
		inp.addClass('txt').attr('name','cat[]').attr('deep',deep);

		//debugger;
		for(var c in child){
			var oc=child[c];
			if(!oc.child) continue;
			oc.path=oc.path || [] ;
			oc.path.push(par.value);
			catTree[oc.path.join('_')+'_'+oc.value]=oc;
			var op=$('<option></option>');
			op.text(c).val(c).data('oc',oc);
			
			select.append(op);
		}
		span.append(select);
		span.append(inp);
		inp.bind('input',function(){
			var deep=$(this).attr('deep')*1;
			var oc=$('#deep_'+deep).find('option[value="'+this.value+'"]').data('oc');
			if(!oc){
				oc={
					deep:	deep+1,
					value:	"",
					child:	[]
				};
			}
			makeSelect(oc,oc.child,deep+1);
		});
	}
	
	makeSelect(d,childs,0);
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
<div hidden>	
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
</div>
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
	var data=getVal('id|name|partner|create_time|owner|pm|allow_add|cust_field');
	data.category=[];
	$('#cat_span input.txt').each(function(i){
		var v=this.value.trim();
		if(v){
			if(data.category.length==i){
				data.category.push(v);
			}
		}
	});
	data.category=data.category.join(<?=json_encode(Project::$split)?>);
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