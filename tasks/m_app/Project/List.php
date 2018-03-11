<?
$allowAdmin=[1,9];
if(in_array(getVal($_SESSION,'admin',0),$allowAdmin)){
?>
<script>
addIcon('mobile/add.png','<?=mkLink('Project','edit',['id'=>-1])?>');
</script>

<?}?>
<script>TITLE('專案清單')</script>
<script>
$('#cat').append('<option value="" selected>[不指定]</option>');
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
<div id="list"></div>
<script>
function ref(){

	Ajax('Project','getManageList',null,function(d){
		var list=$("#list").empty();
		
		d.forEach(function(b){
			with(b){
				list.append(
				'\
				<a class="list" href="<?=mkLink('Task','manage')?>'+'&pid='+id+'">\
					<div class="w">\
						<div class="ltitle">'+name+'</div>\
						<div class="bot fix">\
							<div class="name">'+USERS[owner]+'</div>\
							<div class="desc">'+USERS[pm]+'</div>\
						</div>\
					</div>\
				</a>\
				'
				)
			}
		});
	});
}
ref();
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

</script>