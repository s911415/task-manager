<br/>
<?
$allowAdmin=[1,9];
if(in_array(getVal($_SESSION,'admin',0),$allowAdmin)){
?>
<div class="right" style="margin-top:.35rem">
	<a href="<?=mkLink('Project','edit',['id'=>-1])?>">
		<button class="bt">新增專案</button>
	</a>
</div>
<?}?>

<h2 class="title" id="fdiho">專案管理</h2>
<div class="clear"></div>
<script>
$(function(){
	setTimeout(function(){
		var t=$("#fdiho");
		TITLE(t.text().trim());
		t.hide();
	},200);
	
});
</script>
<div class="flex">
	<span style="width:250px">
		<?=Inp('cat','|專案分類','<select class="txt" onchange="ref()"><option value="" selected>[不指定]</option></select>',false)?>
	</span>
	<span class="hr">&nbsp;</span>
	<?=Inp('name','','<input type="search" class="txt" placeholder="搜尋專案" oninput="ref()" onchange="ref()"/>',false)?>
</div>
<script>
Ajax('Project','getAllCategory',null,function(d){
	d.forEach(function(b){
		var v=b;
		if(!b){
			 b="[未分類]";
		}
		$("#cat").append('<option value="'+v+'">'+b+'</option>');
	});
});


</script>
<style>
#headdd .task{
  background: none;
  box-shadow: none;
  font-size: .8rem;
  color: #888;
  font-weight: bold;
  padding-top: 0;
  padding-bottom: .5rem;
}
#headdd .task::before{display:none;}
#headdd .task .row{  margin-top: 4px;margin-bottom: 4px;}
</style>
<div id="headdd">
	<div class="task">
		<div class="row project_name">專案經理</div>
		<div class="row title">專案名稱</div>
		<div class="row project_name">
			建立者
		</div>
		<div class="row action">功能</div>
		<div class="row deadline_time">建立日期</div>
	</div>
</div>

<div id="list"></div>

<!--
<table class="table" id="plist" style="display:none">
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
-->
<style>
.cat{
  display: inline-block;
  font-size: .75rem;
  background: #DDD;
  color: #666;
  margin-right: .25rem;
  padding: .2rem .25rem;
}

.row.project_name{
	width:6rem;
}
</style>
<script>
function mkCat(cat){
	var cat=cat.trim().split(',');
	if(cat.length==1 && cat[0]=="") return '';
	var aa=[];
	cat.forEach(function(c){
		aa.push('<div class="cat">'+c+'</div>');
	});
	return aa.join('');
}
function ref(){
	$("#plist").show();

	var data=getVal('cat|name');
	if($("#cat")[0].selectedIndex==0) delete data.cat;
	Ajax('Project','getManageList',data,function(d){
		var list=$("#list").empty();
		var buf=[];
		d.forEach(function(b){
			with(b){
				var dd=create_time.replace(/-/g,' ').split(' ');
				html=$(
					'<div class="task" data-pid="'+id+'">\
						<div class="row project_name">'+USERS[pm]+'</div>\
						<div class="row title"><span class="cate">'+mkCat(category)+'</span><span style="-webkit-flex:1;overflow:hidden;text-overflow:ellipsis">'+name+'</span></div>\
						\
						<div class="row project_name">'+USERS[owner]+'</div>\
						\
						<div class="row action">\
							<div class="act_icon edit" title="編輯" allow="1"></div>\
						</div>\
						<div class="row deadline_time">'+dd[1]+'/'+dd[2]+'</div>\
						\
					</div>'
				);
			}
			list.append(html);
		/*
			with(b){
				list.append(mkTr([
					'<span class="id">'+ProjectId(id)+'</span>',
					!category?'未指定':category,
					name,
					USERS[owner],
					USERS[pm],
					done==0?'X':'V',
					getButton(b)
				]));
			}
		*/
		});
		
		bindEvent();
		/*
		setTableWidth(list);
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
		});
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
	
	$('.task .edit').click(function(e){
		var t=$(this);
		var pid=t.parents('.task').data('pid');
		if(t.attr('allow')=='1'){
			location.href="<?=mkLink('Project','edit')?>&id="+pid;
		}else{
			alert('您無權限編輯工作');
		}
		
		e.stopPropagation();
	});
	
	$('.task').click(function(){
		var t=$(this);
		var tid=t.data('pid');
		location.href="<?=mkLink('Task','List')?>&pid="+tid;
	});
}
ref();

</script>