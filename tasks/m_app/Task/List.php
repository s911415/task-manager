<?
$pid=getVal($_GET,'pid','');
$pinfo=Project::getProject($pid);
?>
<script>
TITLE('#'+ProjectId(<?=$pinfo->id?>)+' '+<?=json_encode($pinfo->name)?>);
if(<?=json_encode($pid)?>!=''){

}
</script>
<div class="row" id="filter" style="display:none">
	<div class="col">
		<label>專案</label>
		<select id="pid" class="txt" def="<?=$pid?>">
			<option value="">[可選]</option>
			<?
			$gid=Project::getGroupPid($_SESSION['id']);
			$ginfo=Project::getNames($gid);
			foreach($ginfo as $k=>$v){
				echo '<option value="'.$k.'">'.e($v).'</option>';
			}
			?>
		</select>
	</div>
	
	<div class="col">
		<label>期限</label>
		<select id="deadline_time" class="txt">
			<option value="1=1">[不指定]</option>
			<option value="DATEDIFF(deadline_time,NOW())<=3 AND DATEDIFF(deadline_time,NOW())>=0">3天內</option>
			<option value="DATEDIFF(NOW(),deadline_time)>0">已超過</option>
		</select>
	</div>
	
	<div class="col">
		<label>預計完成日</label>
		<select id="end_time" class="txt">
			<option value="1=1">[不指定]</option>
			<option value="DATEDIFF(end_time,NOW())<=3 AND DATEDIFF(end_time,NOW())>=0">3天內</option>
			<option value="DATEDIFF(NOW(),end_time)>0">已超過</option>
		</select>
	</div>
	
	<div class="col">
		<label>優先順序</label>
		<script>
		(function(){
			var p=getPriority();
			var h='';
			for(var i in p){
				h+='\
				<label class="nos">\
					<input type="checkbox" name="priority" value="'+i+'" checked/> \
					'+getPriority(i)+'\
				</label>';
			}
			document.write(h);
		})();
		</script>
	</div>
	
	<div class="col hide">
		<label>其他</label>
		<label class="nos">
			<input type="checkbox" value="<?=$_SESSION['id']?>" id="resp_id" 
			<?
			$allowAll=[9,2,1];
			if(!in_array($_SESSION['admin'],$allowAll)){
				echo 'checked';
			}
			?>
			/>
			只顯示我負責的工作
		</label>
	</div>
</div>

<div id="list"></div>
<script>
function ref(){
	var Data=getVal('end_time|deadline_time|pid|resp_id');
	if(Data.pid==""){
		Data.pid=[-1];
		$("#pid option").each(function(){
			if(!isFinite(this.value) || this.value==="") return;
			Data.pid.push(this.value);
		});
		Data.pid=Data.pid.join(',');
	}
	Data.priority=[-1];
	$('#filter [name="priority"]:checked').each(function(){
		Data.priority.push(this.value);
	});
	
	Data.priority=Data.priority.join(',');
	
	Ajax('Task','getList',Data,function(d){
		var list=$("#list").empty();
		var html='';
		d.forEach(function(b){
			with(b){
				html=$('\
				<a class="list" href="<?=mkLink('Task','show')?>'+'&pid='+project_id+'&id='+id+'">\
					<div class="w">\
						<div class="ltitle">#'+(TaskId(project_id,project_no).split('-')[1])+' '+title+'</div>\
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