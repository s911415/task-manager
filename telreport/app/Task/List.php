<?	
$today=strtotime('today');
$tomorrow=$today+86400;
$today_s=date('Y-m-d H:i:s',$today);
$tomorrow_s=date('Y-m-d H:i:s',$tomorrow);
?>
<script>
var fedit=false;
</script>
<div class="row">
	<h2 class="title left" style="margin: .25rem 0;" id="fdiho">
	<?php
	$pid=Input::get('pid');
	$pn=Project::getName($pid);
	if($pn) echo '<span style="color:blue">'.$pn.'</span>';
	?>
	我的工作
	</h2>
	<script>
	$(function(){
		setTimeout(function(){
			var t=$("#fdiho");
			TITLE(t.text().trim());
			t.hide();
		},200);
		
	});
	</script>
	<div class="left flex notccc" id="ojjrtj" style="margin:0.5rem 0 0 1rem;">
	工作優先程度：
		<?foreach(range(1,3) as $r){
		?>
		<div class="bk" >
			<div class="p<?=$r?> bb"></div>
			<script>document.write(getPriority(<?=$r?>).split(' - ')[1])</script>
		</div>
		<?
		}?>
	</div>
	
	<div class="right" id="spp">
		
	</div>
	
	
	<div class="clear"></div>
</div>
<style>
.bk{
	width:5rem;
}
.bb{
	width:1rem;
	height:1rem;
	display:inline-block;
	margin-right:.5rem;
	vertical-align:top;
	border:1px solid #CCC;
}
.track,.judge{display:none}
</style>
<div class="clear"></div>
<div id="fpo">
	<div id="filter" class="row">
		<div class="wh " wh="DATEDIFF(deadline_time,NOW())<=1 AND done=0"> <!-- AND DATEDIFF(deadline_time,NOW())>=0-->
			<div class="whic today_dead"></div>
			本日截止及逾期
		</div>
		<div class="wh " wh="done=2"> <!-- AND DATEDIFF(deadline_time,NOW())>=0-->
			<div class="whic deny"></div>
			被退件
		</div>
		<div class="wh" onclick="addTask()" wh="0">
			<div class="whic add"></div>
			新增工作
		</div>
		<div class="wh sel" wh="DATEDIFF(deadline_time,NOW())<=3 AND DATEDIFF(deadline_time,NOW())>=0 AND done=0">
			<div class="whic three_dead"></div>
			三日內截止
		</div>
		<div class="wh" wh="done=0">
			<div class="whic todo"></div>
			全部未完成
		</div>
		<div class="wh" wh="DATEDIFF(start_time,NOW())<=3 AND DATEDIFF(start_time,NOW())>=0 AND done=0">
			<div class="whic new_add"></div>三日內新增
		</div>
		<div class="wh" wh="DATE(finish_time)=CURDATE() AND progress=100">
			<div class="whic adone"></div>
			本日完成
		</div>
		<div class="wh" wh="done=1">
			<div class="whic adone"></div>
			全部完成
		</div>
		<!--<div class="wh" wh="DATEDIFF(start_time,NOW())<=3 AND DATEDIFF(start_time,NOW())>=0 AND done=0">三日內新增</div>-->	
		
		
		<div class="col hide">
			<label>其他</label>
			<label class="nos">
				<input type="checkbox" value="<?=$_SESSION['id']?>" id="resp_id" 
				<?
				//$allowAll=[9,2,1];
				$allowAll=[9,2,1];
				if(false || !in_array($_SESSION['admin'],$allowAll)){
					echo 'checked';
				}
				?>
				/>
				只顯示我負責的工作
			</label>
		</div>
		<script>
		function showMore(){
			$('#more').addClass('show');
			$("#sless").css('display','block');
			$("#smore").hide();
		}
		function hideMore(){
			$('#more').removeClass('show');
			$("#sless").hide();
			$("#smore").css('display','block');
		}
		</script>
		<div class="hide">
		<?=Inp('track','track','<input type="checkbox" value="'.Session::get('id').'"/>')?>
		</div>
		<script>
		var fwh=$('#filter .wh[wh]');
		fwh.each(function(i){
			var t=$(this);
			t
			.attr('ii',i)
			.attr('txt',t.text().trim());
		}).click(function(){
			fwh.removeClass('sel');
			$(this).addClass('sel');
			ref();
			location.hash='#wh'+$(this).attr('ii');
		});
		</script>
	</div>
</div>
<div class="row">

	<div id="more">
		<div class="flex">
			<div class="row">
				<?
					$p=['<option value="">[可選]</option>'];
					$gid=Project::getGroupPid($_SESSION['id']);
					$ginfo=Project::getNames($gid);
					foreach($ginfo as $k=>$v){
						$p[]='<option value="'.$k.'">'.e($v).'</option>';
					}
					echo Inp('pid','|專案','<select class="txt" def="'.Input::get('pid').'" onchange="ref()">'.implode('',$p).'</select>',false);
				?>
			</div>
			
			<div class="row">
				<?=Inp('search','','<input type="search" class="txt" placeholder="Search..." oninput="ref()"/>')?>
				
			</div>
		</div>
		<div class="flex">
			<?=Inp('progress','進度','<select class="txt">
				<option value="BETWEEN 0 AND 20">0~20%</option>
				<option value="BETWEEN 21 AND 40">21~40%</option>
				<option value="BETWEEN 41 AND 60">41~60%</option>
				<option value="BETWEEN 61 AND 80">61~80%</option>
				<option value="BETWEEN 81 AND 100">81~100%</option>
			</select>')?>
		</div>
		
	</div>
	<div class="row clear">
		<a href="#" onclick="return showMore()" id="smore">Show More</a>
		<a href="#" onclick="return hideMore()" id="sless" style="">Show Less</a>
	</div>
</div>
<style>
#headdd .task{
  background: none;
  box-shadow: none;
  font-size: .8rem;
  color: #888;
  font-weight: bold;
  padding-top: 0;
  padding-bottom: .5rem;
}
#headdd .task::before{display:none;}
#headdd .task .row{  margin-top: 4px;margin-bottom: 4px;}
</style>
<div id="headdd">
	<div class="task">
		<div class="row project_name">專案名稱</div>
		<div class="row resp">專案經理</div>
		<div class="row title">工作名稱 - 工作描述
		</div>
		<div class="row action">
			功能&nbsp;
		</div>
		<div class="row deadline_time">期限</div>
		<div class="row progress">進度</div>
		<div class="row eval">狀態</div>
	</div>
</div>

<div id="list"></div>
<!--

<table class="table" style="table-layout:fixed;">
	<thead>
		<tr>
			<th>標題</th>
			<th width="85">負責人</th>
			<th width="85">PM</th>
			<th width="60">期限</th>
			<th width="75">執行狀態</th>
			<th width="165"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
-->
<script>
function ref(){
	var Data=getVal('pid|resp_id|track|search');
	if(!$('#resp_id').prop('checked')){
		delete Data['resp_id'];
	}
	if(Data.pid==""){
		Data.pid=[-1];
		$("#pid option").each(function(){
			if(!isFinite(this.value) || this.value==="") return;
			Data.pid.push(this.value);
		});
		Data.pid=Data.pid.join(',');
	}
	
	Data.tabf=$('.wh.sel').attr('wh');
	var list=$("#list").empty();
	list.append('<div class="waitIcon"></div>');
	Ajax('Task','getList',Data,function(d){
		var html='';
		list.empty();
		d.forEach(function(b){
			with(b){
				var dd=deadline_time.split('/');
				var ddd=$('<span>'+description+'</span>').text();
				html=$(
					'<div class="task p'+priority+'" data-tid="'+id+'">\
						<div class="row project_name">'+project_name+'</div>\
						<div class="row resp">'+USERS[resp_id]+'</div>\
						<div class="row title">'+title+'<div class="descript">'+ddd+'</div></div>\
						\
						<div class="row action">\
							<div class="act_icon edit" title="編輯" allow="'+(allowEdit || fedit?'1':'0')+'"></div>\
							<div class="act_icon track" title="追蹤"></div>\
							<div class="act_icon judge" title="稽核"></div>\
							<div class="act_icon report" title="回報"></div>\
						</div>\
						\
						<div class="row deadline_time">'+dd[1]+'/'+dd[2]+'</div>\
						<div class="row progress">'+(progress)+'%</div>\
						<div class="row eval">'+(getEval(evaluate).split(' - ')[1])+'</div>\
						\
					</div>'
				);
				/*
				html=$(mkTr([
					//TaskId(project_id,project_no),
					//show_id,
					//project_name,
					'<div class="title">['+(getPriority(priority).split(' - ')[1])+']'+title+'</div>',
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
				*/
			}
			list.append(html);
		});
		if(d.length==0){
			list.append('<div style="  margin: 1rem 0;text-align: center;">您沒有'+$('.wh.sel').text()+'的工作</div>');
		}
		bindEvent();
	});
}
function bindEvent(){
	$('.task .track').click(function(e){
		var t=$(this);
		var tid=t.parents('.task').data('tid');
		location.href="<?=mkLink('Task','track')?>&id="+tid+'&back='+encodeURIComponent(location.href);
		e.stopPropagation();
	});
	$('.task .judge').click(function(e){
		var t=$(this);
		var tid=t.parents('.task').data('tid');
		location.href="<?=mkLink('Task','judge')?>&id="+tid+'&back='+encodeURIComponent(location.href);
		e.stopPropagation();
	});
	$('.task .report').click(function(e){
		var t=$(this);
		var tid=t.parents('.task').data('tid');
		location.href="<?=mkLink('Task','report')?>&id="+tid+'&back='+encodeURIComponent(location.href);
		e.stopPropagation();
	});
	$('.task .edit').click(function(e){
		var t=$(this);
		var tid=t.parents('.task').data('tid');
		if(t.attr('allow')=='1'){
			location.href="<?=mkLink('Task','edit')?>&id="+tid+'&back='+encodeURIComponent(location.href);
		}else{
			alert('您無權限編輯工作');
		}
		
		e.stopPropagation();
	});
	$('.task').click(function(){
		var t=$(this);
		var tid=t.data('tid');
		t.find('.act_icon:visible:last').click();
		//location.href="<?=mkLink('Task','show')?>&tid="+tid+'&back='+encodeURIComponent(location.href);
	});
}

function addTask(){
	location.href=<?=json_encode(mkLink('Task','add',[
			'pid'	=>	Input::get('pid',-1)
		]))?>+"&back="+encodeURIComponent(location.href);
}
$("#filter input,#filter select").bind('mouseup keyup change',function(e){
	console.log(e);
	
});
$(function(){
	var ii=1,mm=location.hash.match(/^#wh(\d+)/);
	if(mm){
		ii=mm[1]*1+1;
	}
	$('.wh:nth-child('+ii+')').click();
});
</script>