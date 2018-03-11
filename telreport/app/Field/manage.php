<a href="<?=mkLink('Field','edit',[
	'id'	=>	-1
])?>">
	<button class="bt right">新增欄位</button>
</a>

<h2 class="title">
	自訂欄位列表
</h2>
<table class="table">
	<thead>
		<tr>
			<th width="30">#</th>
			<th width="20%">名稱</th>
			<th width="60">類型</th>
			<th>預設值</th>
			<th>可能的值</th>
			<th width="140"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
<script>
function ref(){
	Ajax('CustomField','getList',{},function(d){
		var list=$("#list").empty();
		d.forEach(function(b){
			with(b){
				list.append(mkTr([
					id,
					name,
					type,
					def,
					p_val,
					'<button class="bt edit" fid="'+id+'">編輯</button>'+
					'<button class="del" fid="'+id+'">刪除</button>'+
					''
				]));
			}
		});
		bindEvent();
	});
}

function bindEvent(){
	$('.edit').click(function(){
		var fid=this.getAttribute('fid');
		location.href="<?=mkLink('Field','edit')?>&id="+fid;
	});
	$('.del').click(function(){
		if(confirm('Are you sure?')){
			var fid=this.getAttribute('fid');
			Ajax('Field','delete',{
				fid:	fid
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