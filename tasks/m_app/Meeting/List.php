<div class="w">

<div class="right">
	<a href="<?=mkLink('Meeting','edit',[
		'id'	=>	-1
	])?>">
		<button class="bt">新增會議</button>
	</a>
</div>
<h2 class="title">會議清單</h2>
<table class="table">
<thead>
	<tr>
		<th width="35">#</th>
		<th>名稱</th>
		<th width="100">主持人</th>
		<th width="70">參與人數</th>
		<th width="160">開始時間</th>
		<th width="160">結束時間</th>
		<th width="210"></th>
	</tr>
</thead>
<tbody id="list"></tbody>
</table>
<script>
function ref(){
	var data={};
	Ajax('Meeting','getList',data,function(d){
		var list=$("#list").empty();
		d.forEach(function(b){
			with(b){
				list.append(mkTr([
					id,
					name,
					USERS[holder],
					'<div class="r">'+join_uid.split(",").length+'</div>',
					start_time,
					end_time,
					mkButton(b)
				]));
			}
		});
		bindEvent();
	});
}

function bindEvent(){
	$('.del').click(function(){
		if(!confirm('您確定要刪除此會議紀錄?')) return;
		var id=$(this).data('id');
		Ajax('Meeting','delMeeting',{
			id:	id
		},function(d){
			if(d){
				ref();
				msgbox('操作完成','success');
			}else{
				msgbox('操作失敗','error');
			}
		});
	});
}

function mkButton(b){
var a=[],allowMod=<?
	$allowMod=[1,9];
	if(in_array($_SESSION['admin'],$allowMod)){
		echo 'true';
	}else{
		echo 'false';
	}
?>;
with(b){
	//a.push('<a href="<?=mkLink('Meeting','getLog',['sp'=>1])?>&id='+id+'" target="_blank"><button class="bt">查看記錄</button></a>');
	a.push('<a href="<?=mkLink('Meeting','show')?>&id='+id+'"><button class="bt">查看</button></a>');
	allowMod && a.push('<a href="<?=mkLink('Meeting','edit')?>&id='+id+'"><button>編輯</button></a>');
	allowMod && a.push('<button class="del" data-id="'+id+'">刪除</button>');
}
return a.join('');
}

ref();
</script>

</div>