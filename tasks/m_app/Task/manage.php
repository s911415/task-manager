<?
$pid=getVal($_GET,'pid','');
$pinfo=Project::getProject($pid);
?>
<script>
TITLE('#'+ProjectId(<?=$pinfo->id?>)+' '+<?=json_encode($pinfo->name)?>);
if(<?=json_encode($pid)?>!=''){

}
</script>
<script>
addIcon('mobile/add.png','<?=mkLink('Task','show',[
	'id'	=>	-1,
	'pid'	=>	$pinfo->id,
	'edit'	=>	'true',
	'from'	=>	'manage'
])?>');
</script>
<div id="list"></div>
<script>
function ref(){
	var Data={
		pid:	<?=$pid?>
	};
	
	Ajax('Task','getList',Data,function(d){
		var list=$("#list").empty();
		var html='';
		d.forEach(function(b){
			with(b){
				html=$('\
				<a class="list" href="<?=mkLink('Task','show')?>'+'&from=manage&id='+id+'&pid='+project_id+'">\
					<div class="w">\
						<!--<div class="ltitle">#'+(TaskId(project_id,project_no).split('-')[1])+' '+title+'</div>--> \
						<div class="ltitle">'+show_id+' '+title+'</div>\
						<div class="bot fix">\
							<div class="name">'+USERS[resp_id]+'</div>\
							<div class="desc">'+deadline_time+'</div>\
						</div>\
					</div>\
				</a>\
				')
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