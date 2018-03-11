<h2 class="title">
	使用者紀錄
</h2>
<div class="row fix">
	<div class="left">
		<form class="form">
			<label for="user" class="nos">
				使用者名稱
				<input type="text" id="user"/>
			</label>
			<button class="bt">搜尋</button>
		</form>
		<script>
		function fcb(){
			ref();
		}
		</script>
	</div>
</div>

<table class="table">
<thead>
	<tr>
		<th width="120">使用者</th>
		<th>操作</th>
		<th width="150">時間</th>
		<th width="150">IP</th>
	</tr>
</thead>
<tbody id="list"></tbody>
</table>

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