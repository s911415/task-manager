<?
$allowAdmin=[1,9];
if(in_array(getVal($_SESSION,'admin',0),$allowAdmin)){
?>
<div class="right">
	<a href="<?=mkLink('Project','edit',['id'=>-1])?>">
		<button class="bt">新增專案</button>
	</a>
</div>
<?}?>

<h2 class="title">專案清單</h2>
<div class="row">
	<div class="left">
		<label for="cat" style="display:inline">專案分類</label>
		<select id="cat" class="txt" style="display:inline;width:auto" onchange="ref()"></select>
	</div>
</div>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>分類</th>
			<th>名稱</th>
			<th>提出者</th>
			<th>PM</th>
			<th>結案</th>
			<th width="250"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
<script>
function ref(){
	Ajax('Project','getManageList',null,function(d){
		var cat=[];
		var list=$("#list").empty();
		var buf=[];
		d.forEach(function(b){
			with(b){
				buf.push(mkTr([
					'<span class="id">'+ProjectId(id)+'</span>',
					!category?'未指定':category,
					name,
					USERS[owner],
					USERS[pm],
					done==0?'X':'V',
					getButton(b)
				]));
				if(cat.indexOf(category)==-1 && category) cat.push(category);
			}
		});
		var catS=$("#cat"),pV=catS.val();
		catS.empty();catS.append('<option value="">[不指定]</option>');
		cat.forEach(function(c){
			catS.append('<option>'+c+'</option>');
		});
		
		if(pV) catS.val(pV);
		for(var i=0;i<buf.length;i++){
			if(pV && d[i].category!=pV) continue;
			list.append(buf[i]);
		}
		
		bindEvent();
		setTableWidth(list);
		/*
		$("#list").sortable({
			placeholder:	'highlight',
			update:	function(){
				var tr=list.find('>tr');
				var data={};
				tr.each(function(i){
					var id=$(this).find('.id').text();
					id=parseInt(id,10);
					data[id]=i;
				});
				Ajax('Project','setOrder',data,function(){});
			}
		})
		*/
	});
}

function getButton(d){
var a=[],admin=<?=$_SESSION['admin']?>;

with(d){
	a.push('<button class="manageProject bt" pid="'+id+'">工作管理</button>');
	admin!=0 && a.push('<button class="editProject" pid="'+id+'">編輯專案</button>');
	admin!=0 && a.push('<button class="delProject" pid="'+id+'">刪除專案</button>');
}

return a.join('\n');
}

function bindEvent(){
	$('.manageProject').click(function(){
		var pid=this.getAttribute('pid');
		location.href='<?=mkLink('Task','manage')?>&pid='+pid;
	});
	$('.editProject').click(function(){
		var pid=this.getAttribute('pid');
		location.href='<?=mkLink('Project','edit')?>&id='+pid;
	});
	$('.delProject').click(function(){
		var pid=this.getAttribute('pid');
		if(
			<?=getVal($_SESSION,'admin',0)?>>=1 &&
			confirm('Are you sure?')
		){
			Ajax('Project','delProject',{
				pid:	pid
			},function(d){
				if(d){
					ref();
					msgbox('刪除成功','success');
				}else{
					msgbox('刪除失敗','error');
				}
			})
		}
	});
}


ref();
</script>