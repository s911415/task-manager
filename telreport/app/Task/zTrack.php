<h2 class="title">追蹤工作清單</h2>
<style>
#list{
	font-size:12px;
	font-family:'新細名體';
}
</style>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>編號</th>
			<!--<th>專案</th>-->
			<th>標題</th>
			<th width="85">負責人</th>
			<th width="60">開始</th>
			<th width="60">期限</th>
			<th width="75">執行狀態</th>
			<th width="75">優先順序</th>
			<th width="50">進度</th>
			<th width="40">結案</th>
			<th width="165"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>

<script>
function ref(){
	Ajax('Track','getTasks',{
		uid:	<?=json_encode($_SESSION['id'])?>
	},function(d){
		var list=$("#list").empty();
		var html='';
		d.forEach(function(b){
			with(b){
				html=$(mkTr([
					TaskId(project_id,project_no),
					show_id,
					//project_name,
					'<div class="title">'+title+'</div>',
					USERS[resp_id],
					start_time,
					deadline_time,
					getEval(evaluate),
					getPriority(priority),
					progress+'%',
					//'<input type="checkbox" value="'+id+'" class="taskDone" '+(done==1?'checked':'')+'/>',
					done==0?'否':'是',
					'\
						<button class="showTask bt" tid="'+id+'">檢視工作</button>\
						<button class="editTask" tid="'+id+'" pid="'+project_id+'">編輯工作</button>\
					'
				]))
				.addClass('task')
				.addClass('p'+priority);
			}
			list.append(html);
		});
		bindEvent();
	});
}
function bindEvent(){
	$('.showTask').click(function(){
		var tid=this.getAttribute('tid');
		location.href='<?=mkLink('Task','show')?>&tid='+tid;
	});
	$('.editTask').click(function(){
		var tid=this.getAttribute('tid');
		var pid=this.getAttribute('pid');
		location.href='<?=mkLink('Task','edit')?>&pid='+pid+'&id='+tid+'&back='+encodeURIComponent(location.href);
	});
}
$("#filter input,#filter select").bind('mouseup keyup change',function(e){
	console.log(e);
	ref();
});
$(ref);
</script>