<a href="<?=mkLink('User','edit',[
	'id'	=>	-1
])?>">
	<button class="bt right">新增使用者</button>
</a>

<h2 class="title">
	使用者列表
</h2>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>帳號</th>
			<th>名稱</th>
			<th>最後登入</th>
			<th>權限</th>
			<th width="140"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
<script>
function ref(){
	Ajax('User','getAllUserList',{},function(d){
		var list=$("#list").empty();
		d.forEach(function(b){
			with(b){
				list.append(mkTr([
					id,
					account,
					name,
					last_login,
					getAdmin(admin),
					'<button class="bt edit" uid="'+id+'">編輯</button>'+
					'<button class="del" uid="'+id+'">刪除</button>'+
					''
				]))
			}
		});
		bindEvent();
	});
}

function bindEvent(){
	$('.edit').click(function(){
		var uid=this.getAttribute('uid');
		location.href="<?=mkLink('User','edit')?>&id="+uid;
	});
	$('.del').click(function(){
		if(confirm('Are you sure?')){
			var uid=this.getAttribute('uid');
			Ajax('User','delete',{
				uid:	uid
			},function(d){
				if(d){
					msgbox('刪除成功','success');
					ref();
				}else{
					msgbox('刪除失敗','error');
				}
			});
		}
		
	});
}
ref();
</script>