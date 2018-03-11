<?
$id=intval($_GET['id']);
$method='修改';
if($id==-1) $method='新增';
?>
<style>
.tlist{
	font-size:12px;
	font-family:'新細名體';
}
</style>
<h2 class="title"><?=$method?>會議</h2>
<form class="form">
	<div class="two fix">
		<?=Inp('name','會議名稱','<input type="text"/>')?>
		<?=Inp('holder','主持人','
		<select class="txt" def="'.$_SESSION['id'].'" disabled></select>
		<script>
		appendGetToSelect("#holder",function(a){
			if(typeof a=="undefined") return USERS;
			
			return USERS[a];
		});
		</script>
		')?>
	</div>
	
	<div class="two fix">
		<?=Inp('start_time','開始時間','<input type="text" class="datetime" value="'.getTime().'"/>')?>
		<?=Inp('end_time','結束時間','<input type="text" class="datetime" value="'.getTime(time()+10*60).'"/>')?>
	</div>
	
	<?=Inp(
		'join_uid',
		'參與人員<button id="editJoin" type="button">編輯成員</button>',
		'<input type="hidden"/><div id="joinList"></div>'
	)?>
	
	<?=Inp('commentLog','會議記錄','<textarea class="txt" rows="5"></textarea>')?>
	<div class="hide">
		<button type="submit" id="submit"></button>
		<?=Inp('id','','<input type="hidden" value="'.$id.'"/>')?>
	</div>
</form>
<div id="tabs"></div>
<div class="row r">
	<button onclick="$('#submit').click()" class="bt">送出</button>
	<a href="<?=mkLink('Meeting','List')?>"><button>返回會議清單</button></a>
</div>
<div class="hide" id="tabContent">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>編號</th>
				<th>專案</th>
				<th>標題</th>
				<th width="60">期限</th>
				<th width="75">執行狀態</th>
				<th width="75">優先順序</th>
				<th width="50">進度</th>
				<th width="80"></th>
			</tr>
		</thead>
		<tbody class="tlist"></tbody>
	</table>
</div>
<script>
<?
if($id!=-1){
	echo 'passVal('.json_encode(Meeting::getMeeting($id)).');
';
}
?>
function refUID(){
	var uid=$('#join_uid').val().split(',');
	var u=[];
	uid.forEach(function(id){
		u.push(USERS[id]);
	});

	$('#joinList').html(u.join('、'));
	
	var tabs=$('#tabs').empty();
	var users='<ul id="users">';
	var tabC='';
	uid.forEach(function(id){
		if(id=="") return;
		users+='<li><a href="#user_'+id+'">'+USERS[id]+'</a></li>';
		tabC+='<div id="user_'+id+'" class="tabC"></div>';
	});
	users+='</ul>';
	
	tabs.append(users+tabC);
	
	try{
		tabs.tabs('destroy');
	}catch(e){}
	finally{
		if(uid.join('')!=''){
			tabs.tabs({
				heightStyleType:	'auto'
			});
			tabs.find('.tabC').append($('#tabContent').html());
			uid.forEach(function(id){
				getTaskList(id);
			});
		}
	}
	
	
}

function getTaskList(uid){
	var Data={
		done:		0,
		resp_id:	uid
	};
	Ajax('Task','getList',Data,function(d){
		var list=$('#user_'+uid+' .tlist').empty();
		d.forEach(function(b){
			with(b){
				list.append(mkTr([
					id,
					show_id,
					project_name,
					title,
					deadline_time,
					getEval(evaluate),
					getPriority(priority),
					progress+'%',
					'\
						<a href="<?=mkLink('Task','show')?>&tid='+id+'" target="showTask"><button>檢視工作</button></a>\
						\
						<a href="<?=mkLink('Task','show',[
							'from'	=>	'manage'
						])?>&tid='+id+'" target="editTask"><button>工作評等</button></a>\
					'
				]));
			}
		});
	});
}

function fcb(){
	var Data=getVal('id|name|holder|join_uid|start_time|end_time|commentLog');
	Ajax('Meeting','edit',Data,function(d){
		if(d){
			msgbox('操作完成','success',function(){
				location.href='<?=mkLink('Meeting','show')?>&id='+d
			});
		}
	});
}

$('#join_uid').change(refUID).change();

$("#editJoin").click(function(){
	var v=$("#join_uid").val();
	window.open(
		'<?=mkLink('User','select',[
			'target'	=>	'join_uid',
			'ispop'		=>	1
		])?>#'+v,
		'selectPartner',
		'width=640,height=480'
	).focus();
});
</script>