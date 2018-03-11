<script>
	TITLE("使用者紀錄");
</script>
<div class="w">
	<form class="form" sc="ref">
		<button class="bt right">搜尋</button>
		<div style="width:calc(100% - 80px);float:left">
			<?=Inp('user','','<input type="text" placeholder="搜尋使用者名稱"/>',false)?>
		</div>
	</form>
</div>

<div class="w">
	<table class="table">
	<thead>
		<tr>
			<th width="120">使用者</th>
			<th>操作</th>
			<th width="150">時間</th>
			<th width="120">IP</th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
	</table>

</div>

<script>
function ref(){
	Ajax('Logger','getLogs',{
		user:	$("#user").val().trim()
	},function(d){
		var list=$("#list").empty();
		d.forEach(function(b){
			with(b){
				list.append(mkTr([
					user,
					act,
					time,
					ip
				]));
			}
		});
	});
}
ref();
</script>