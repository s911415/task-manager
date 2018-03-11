<?
	$id=intval(Input::get('id',-1));
	$pid=intval(Input::get('pid',-1));
	$method='修改';
	$info=Task::getTask($id);
	if($pid==-1 && $id!=-1){
		$pid=Project::getProject($info->project_id)->id;
	}
	if($id==-1){
		$method='新增';
	}
	$method=Input::get('method',$method);
?>
<script>
<?php
$backUrl=Input::get('back',Server::get('HTTP_REFERER','./'));
?>
var backUrl=<?=json_encode($backUrl)?>;
</script>
<script>
TITLE('<?=$method?>工作');
</script>
<p></p>
<form class="form">
	<div class="flex fix">
		<?
		$pop=['<option value="-1">[請選擇]</option>'];
		$gid=Project::getGroupPid(Session::get('id'));
		$ginfo=Project::getNames($gid);
		foreach($ginfo as $k=>$v){
			$pop[]='<option value="'.$k.'" uid="'.(Project::getProject($k)->owner).'">'.e($v).'</option>';
		}
		?>
		<?=Inp('project_id','|專案名稱','<select class="txt" def="'.($pid?$pid:Cookie::get('last_pid')).'">'.implode('',$pop).'</select>',false)?>
		
		<?
			//$respList=Project::getPartner($pid);
			echo Inp('resp_id','|工作人員','<select class="txt" def="'.Session::get('id').'"></select>',false);
		?>
	</div>
	<style>
	#row_title,#row_project_id{-webkit-box-flex:6;-webkit-flex:6;-ms-flex:6;flex:6;}
	#row_resp_id,#row_priority{-webkit-box-flex:2.5;-webkit-flex:2.5;-ms-flex:2.5;flex:2.5;}
	input[type="datetime-local"]{
		max-width:250px;
	}
	#track,#ooo{
		max-width:7.5rem;
	}
	</style>
	
	<div class="flex fix">

		<?=Inp('title','|工作名稱','<input type="text" style=""/>')?>
		<?=Inp('priority','|優先順序','<select class="txt" def="3"></select>',false)?>
	</div>
	
	<div class="flex fix">
	
		<?
			//$respList=Project::getPartner($pid);
			echo Inp('ooo','|專案經理&nbsp;','<select class="txt" disabled></select>',false);
		?>
		<?
			//$respList=Project::getPartner($pid);
			echo Inp('track','|稽核人員','<select class="txt" def="'.($pid?$pid:Cookie::get('track')).'"><option value="">無</option></select>',false);
		?>
	</div>

	<div class="flex fix">
		<?=Inp('start_time','|提出日期','<input type="text" class="datetime" value="'.getTime(null,'HTML').'"/>',false)?>
		<?=Inp('deadline_time','|完成期限','<input type="text" class="datetime" value="'.getTime(time()+60*60,'HTML').'"/>',false)?>
	</div>
	
	<div class="flex">
		<?=Inp('description','描述','<textarea class="txt" rows="3"></textarea>',false)?>
		<div class="" style="width:.20rem"></div>
	</div>
	<!--
	<?=Inp(
		'track',
		'稽核人員<button id="addPart" type="button" style="margin-left:.5rem">修改成員</button>',
		'<input type="hidden"/>
		<div id="plist"></div>',
		false
	)?>
	-->
	<script>
	(function(){
		var list=$("#resp_id,#ooo,#track");
	/*
		var rList=<=json_encode($respList)>;
		
		rList.forEach(function(id){
			list.append(
				'<option value="'+id+'">'+USERS[id]+'</option>'
			);
		});
	*/
		for(var id in USERS){
			list.append(
				'<option value="'+id+'">'+USERS[id]+'</option>'
			);
		}
		list.each(function(){
			var ii=$(this).attr('def');
			this.value=ii;
			$(this).removeAttr('def');
		});
	})();
	$('#project_id').change(function(){
		var op=this.selectedOptions[0];
		if(op && op!==-1){
			$("#ooo").val($(op).attr('uid'));
		}else{
			$("#ooo").val('-1');
		}
	});
	</script>
	<script>
	/*
	$("#track").change(refP);
	function refP(){
		var n=[];
		var d=$("#track").val().split(',');
		d.forEach(function(id){
			n.push(USERS[id]);
		});
		
		var plist=$("#plist");
		if(n.length>0){
			plist.text(n.join('、'));
		}else{
			plist.text('沒有人');
		}
	}
	
	$('#addPart').click(function(){
		var track=$("#track").val();
		window.open(
			'<?=mkLink('User','select',[
				'type'	=>	'checkbox',
				'target'=>	'track',
				'ispop'	=>	1
			])?>#' + track,
			'selectPartner',
			'width=640,height=300'
		).focus();
	});
	*/
	</script>
	<div id="detail" class="hide">
	<?=Inp('progress','進度','<input class="txt" type="number" value="0" min="0" max="100"/>',false)?>
	<?=Inp('status','值行情況說明','<textarea class="txt" rows="4"></textarea>',false)?>

	<?=Inp('end_time','預計完成日期','<input type="text" class="datetime" value="'.getTime(time()+86400,'html').'">',false)?>
	<?=Inp('finish_time','實際完成日期','<input type="text" class="datetime"/>',false)?>

	<?
	$zx=[];
	foreach(range(1,24) as $zzc){
		$zx[]='<option value="'.$zzc.'">'.$zzc.'時</option>';
	}
	$zx=implode('',$zx);
	echo Inp('spent','花費時間','<select class="txt">'.$zx.'</select>',false);
	?>

	<?=Inp('evaluate','執行狀態','<select class="txt" def="3"></select>',false)?>

	<?
	$s=[];
	foreach($scores as $d){
		$s[]='<option>'.e($d).'</option>';
	}
	echo Inp(
		'score',
		'等第',
		'<select class="txt">'.implode('',$s).'</select>',
		false
	)?>


	<?=Inp('done','是否結案','<input type="checkbox"/>',false)?>
	</div>
	
	<?
	if($id!=-1){
		echo Inp('uploadedFile','已上傳檔案','<div ></div>');
	}
	?>
	<script>
	function refFileList(){
		Ajax('File','getFiles',{
			tid:	<?=$id?>,
			pid:	-1
		},function(flist){
			var list=$("#uploadedFile").empty();
			flist.forEach(function(f){
				with(f){
					list.append('\
						<div class="uploadF" href="<?=mkLink('File','getFile')?>&id='+id+'">\
							<div class="fn">'+filename+'</div>\
							<div class="row">\
								<div class="ud">'+upload_time+'</div>\
								<div class="un">'+USERS[owner]+'</div>\
								<div class="fs">'+minByte(filesize)+'</div>\
							</div>\
							<div class="delF" fid="'+id+'">X</div>\
						</div>\
					');
				}
				
			});
			
			$('.delF').click(function(){
				var fid=this.getAttribute('fid');
				var p=$(this.parentNode);
				if(confirm('Are you sure?')){
					Ajax('File','delete',{
						fid:	fid
					},function(d){
						if(d){
							p.remove();
							msgbox('刪除成功','success');
						}else{
							msgbox('刪除失敗','error');
						}
					});
				}
			});
		});
	}
	refFileList();
	</script>
	<div class="clear"></div>
	<?=Inp('upload','上傳檔案','<div ></div>',false)?>
	<script>
	
	</script>
	<div class="hide">
		<?=Inp('show_id','編號','<input type="text" value=""/>')?>
		<script>
		
		TaskId(
		<?
		echo $pid;
		$project_no=0;
		echo ',';
		if($id==-1){
			$project_no=Project::getLastNo($pid);
		}else{
			$project_no=$info->project_no;
		}
		echo $project_no;
		?>
		);
		$('#show_id').val(TaskId('<?=$pid?>','<?=$project_no?>'));
		</script>
		<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
		<?=Inp('project_no','project_no','<input type="hidden" value="'.$project_no.'"/>')?>
		<?=Inp('own_id','own_id','<input type="hidden" value="'.$_SESSION['id'].'"/>')?>
	</div>
	<script>
	appendGetToSelect("#evaluate",getEval);
	appendGetToSelect("#priority",getPriority);
	</script>
	<style>
	#zxcsfdhpoj>*{flex:1;margin-left:.3rem;margin-right:.3rem;}
	#oigo{  min-width: 11rem;}
	#row_upload{
		margin-left:.5rem
	}
	</style>
	
	
	<?
	foreach($info->fields as $k=>$v){
		echo $v;
	}
	?>
	
	<div class="row c fix" >
		<div class="flex" id="zxcsfdhpoj" style="width:92%;margin:1.25rem auto 0;">
			<button class="bt" onclick="window.add_type='new'" style="background:#35AEFF;color:#FFF;">儲存本工作</button>
			
			<button class="" type="button" onclick="back()">取消並返回上一頁</button>
			<script>
			function back(){
				var url=<?=json_encode($backUrl.'&back='.urlencode($backUrl))?>;
				location.href=url;
			}
			</script>
		</div>
	</div>
	<!--
	<div class="row c fix">
		<button class="bt"><?=$method?></button>
		<?
		if(getVal($_GET,'from')=='manage'){
		?>
		<a href="<?=mkLink('Task','manage',[
			'pid'	=>	$pid
		])?>">
			<button type="button">返回工作管理</button>
		</a>
		<?
		}else{?>
		
		<a href="<?=mkLink('Task','List')?>">
			<button type="button">返回工作清單</button>
		</a>

		<a href="<?=mkLink('Task','show',[
			'tid'	=>	$id
		])?>">
			<button type="button">返回工作</button>
		</a>
		<?}?>
	</div>
	-->
</form>
<script>
passVal(<?=json_encode($info)?>);
</script>
<script>
function addFile(){
	var f=$('<input type="file" multiple/>');
	f.one('change',function(){
		if(this.value!='') addFile();
	});
	$("#upload").append(f);
}
addFile();
function fcb(){
	var data=getVal('id|project_id|project_no|show_id|title|own_id|resp_id|progress|status|description|start_time|end_time|finish_time|priority|score|evaluate|done|deadline_time|spent|track');
	getCustField(data);
	Ajax('Task','edit',data,function(d){
		if(d!==false){
			var fs=$("#upload input");
			var len=0;
			var count=0;
			function don(){
				var add_type=window.add_type || 'new';
				switch(add_type){
					case "new":
							location.href=backUrl+'&back='+encodeURIComponent(backUrl);
					break;
					case "new_new":
						location.href=location.href+'&back='+encodeURIComponent(backUrl);;
					break;
				}
			}
			fs.each(function(){
				len+=this.files.length;
				if(len==0){
					don();
				}else{
					for(var i=0;i<this.files.length;i++){
						uploadFile(this.files[i],{
							task_id:	d,
							project_id:	-1
						},function(){
							count++;
							refFileList();
							if(count==len){
								$("#upload").empty();
								addFile();
								don();
							}
						});
					}
				}
				
			});
		}else{
			msgbox('修改失敗','error');
		}
	});
}
</script>
<style>
@media screen and (max-width: 500px) {

	#zxcsfdhpoj{
		width:auto;
		display:block;
	}
	#zxcsfdhpoj button{
		width:100%;
	}
	#zxcsfdhpoj button{
		back
	}
}
</style>