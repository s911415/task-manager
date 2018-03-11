<?
$pid=intval($_GET['pid']);
$pinfo=Project::getProject($pid);
?>
<style>#list{font-size:12px;}</style>
<div class="right">
	<a href="<?=mkLink('Project','manage')?>"><button>返回專案管理</button></a>
	<?
	if(!$_SESSION['admin']==0 || $pinfo->allow_add==1){?>
		<a href="<?=mkLink('Task','edit',[
			'id'	=>	-1,
			'pid'	=>	$pid,
			'from'	=>	'manage'
		])?>"><button class="bt">新增工作</button></a>
	<?}?>
</div>
<h2 class="title"><span style='color:blue'><?=e($pinfo->name)?></span> - 工作管理</h2>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>編號</th>
			<!--<th>專案</th>-->
			<th>標題</th>
			<!--<th>提出者</th>-->
			<th>負責人</th>
			<th>優先順序</th>
			<th>進度</th>
			<th>開始時間</th>
			<th>期限</th>
			<th width="40">結案</th>
			<th width="275"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>

<div class="row c">
	<a href="<?=mkLink('Project','manage')?>"><button>返回專案管理</button></a>

	<?
	if(!$_SESSION['admin']==0 || $pinfo->allow_add==1){?>
		<a href="<?=mkLink('Task','edit',[
			'id'	=>	-1,
			'pid'	=>	$pid,
			'from'	=>	'manage'
		])?>">
			<button class="bt">新增工作</button>
		</a>
	<?}?>
</div>
<script>
function ref(){
	Ajax('Task','getList',{pid:<?=intval($pid)?>},function(d){
		var list=$("#list").empty();
		var html='';
		d.forEach(function(b){
			with(b){
				html=$(mkTr([
					TaskId(project_id,project_no),
					show_id,
					//project_name,
					'<div class="title">'+title+'</div>',
					//USERS[own_id],
					USERS[resp_id],
					getPriority(priority),
					progress+'%',
					start_time,
					deadline_time,
					//'<input type="checkbox" value="'+id+'" class="taskDone" '+(done==1?'checked':'')+'/>',
					done==0?'否':'是',
					getButton(b)
				]))
				.addClass('task')
				.addClass('p'+priority);
			}
			list.append(html);
		});
		bindEvent();
	});
}

function getButton(d){
var a=[],admin=<?=$_SESSION['admin']?>;

with(d){
	if(admin==0){
		a.push('<button class="showTaskNormal bt" tid="'+id+'">檢視</button>');
	}else{
		a.push('<button class="showTask bt" tid="'+id+'">檢視</button>');
	}
	admin!=0 && a.push('<button class="track" tid="'+id+'">追蹤管理</button>');
	admin!=0 && a.push('<button class="editTask" tid="'+id+'" pid="'+project_id+'">編輯</button>');
	admin!=0 && a.push('<button class="delTask" tid="'+id+'">刪除</button>');
}

return a.join('\n');
}

function bindEvent(){
	$('.showTask').click(function(){
		var tid=this.getAttribute('tid');
		location.href='<?=mkLink('Task','show',[
			'from'	=>	'manage'
		])?>&tid='+tid;
	});
	$('.showTaskNormal').click(function(e){
		var tid=this.getAttribute('tid');
		window.open('<?=mkLink('Task','show')?>&tid='+tid);
		e.preventDefault();
	});
	$('.editTask').click(function(){
		var tid=this.getAttribute('tid');
		var pid=this.getAttribute('pid');
		location.href='<?=mkLink('Task','edit',[
			'pid'	=>	$pid,
			'from'	=>	'manage'
		])?>&id='+tid;
	});
	$('.delTask').click(function(){
		var tid=this.getAttribute('tid');
		if(confirm('Are you sure?')){
			Ajax('Task','delTask',{
				tid:	tid
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
	
	$('.track').click(function(){
		var tid=this.getAttribute('tid');
		//'./index.php?act=User&func=select&type=checkbox&target=partner&ispop=1#' + partner,
		var w=window.open(
			'about:blank',
			'selectPartner',
			'width=640,height=300'
		);
		Ajax('Track','getTrackList',{
			tid:	tid
		},function(d){
			var rndId="";
			while(rndId.length<15){
				rndId+=String.fromCharCode(Math.floor(Math.random()*26)+97);
			}
			rndId+=new Date().getTime();
			
			var inp=$('<input type="hidden" id="'+rndId+'"/>');
			inp.change(function(){
				Ajax('Track','setTrackList',{
					tid:	tid,
					uids:	this.value
				},function(d){
					if(d){
						msgbox('修改完成','success',function(){
							inp.remove();
						});
					}
				});
			});
			
			$('body').append(inp);
			
			w.location.replace("<?=mkLink('User','select',[
				'type'	=>	'checkbox',
				'ispop'	=>	1
			])?>&target="+rndId+'#'+d.join(','));
			w.focus();
		});
	});
	
	$('.taskDone').change(function(){
		Ajax('Task','setDone',{
			tid:	this.value,
			done:	this.checked?1:0
		},function(d){
			if(d){
				msgbox('操作完成','success');
			}else{
				msgbox('操作失敗','error')
			}
		});
	});
}


ref();
</script>