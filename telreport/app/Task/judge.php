<?
	$id=intval(Input::get('id',-1));
	$pid=intval(Input::get('pid',-1));
	$method='稽核';
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
TITLE('工作稽核');
</script>
<div class="right">
	<a href="<?=mkLink('Task','show')?>&tid=<?=$id?>" title="詳細資料">
		<div class="whic info"></div>
	</a>
</div>
<h2 class="title">
	<span style="padding:0 .5rem;font-size:85%;" class="p<?=$info->priority?>">[<script>document.write(getPriority(<?=$info->priority?>).split(' - ')[1])</script>]</span>
	
	[<?=$info->project_name?>]<?=$info->title?>
</h2>
<form class="form">
	<div style="background:#FEFCF5;padding:.75rem 1rem 0;border:1px solid #CCC;margin:.5rem 0">
		<label><b>工作描述</b></label>
		<div><?=$info->description?></div>
		<div class="row flex fix">
			<?=Inp('ooo','|主持人','<span style="">'.User::getName($info->project->owner).'</span>',false)?>
			<?=Inp('PM','|專案經理','<span style="">'.User::getName($info->report_to).'</span>',false)?>
			<?=Inp('own_id','|工作人員','<span style="">'.User::getName($info->own_id).'</span>',false)?>
			<?
			$tun=[];
			foreach($info->track as $tuid){
				$tun[]=User::getName($tuid);
			}
			if(count($tun)==0){
				$tun[]='無';
			}
			echo Inp('track','|稽核人員','<span style="">'.implode('、',$tun).'</span>',false);
			?>
		</div>
		
		<div class="flex row fix">
			<?
			echo Inp('start_time','|提出日期','<span >'.substr($info->start_time,0,16).'</span>',false);
			
			$sh=$info->deadline_time;
			if($info->last_change_deadline!=-1){
				$sh.=' (最後由<i>'.User::getName($info->last_change_deadline).'</i>編輯)';
			}
			?>
		</div>
	</div>
	
	
	<div class="flex fix">
	
		<?=Inp('deadline_time','|完成期限','<input type="text" class="datetime" value="'.getTime(time()+86400,'html').'">',false)?>
		<?
		$s=[];
		foreach($scores as $d){
			$s[]='<option>'.e($d).'</option>';
		}
		echo Inp(
			'score',
			'|　　等第',
			'<select class="txt">'.implode('',$s).'</select>',
			false
		)?>
		
		<?=Inp('done','|是否結案','<span >
			<input type="radio" name="done" value="1" checked> 結案 &emsp;
			<input type="radio" name="done" value="2"> 退回
			
		</span>',false)?>
	</div>
	
	<div class="flex fix">
		<?=Inp('end_time','|預計完成','<input type="text" class="datetime" value="'.getTime(time()+86400,'html').'">',false)?>
		<?=Inp('finish_time','|實際完成','<input type="text" class="datetime" value="'.getTime(time(),'html').'"/> <span class="setNow">NOW</span>',false)?>
		<style>
		#finish_time{
			width:calc(100% - 3.5rem);
			float:left;
		}
		</style>
	</div>
	
	<style>/*
	#row_done input {
  width: 1.75rem;
  height: 1.75rem;
}*/
	/*#row_PM,#row_track{-webkit-box-flex:0;-webkit-flex:none;-ms-flex:none;flex:none;margin-right:1rem}*/
	#row_deadline_time{-webkit-box-flex:2;-webkit-flex:2;-ms-flex:2;flex:2;}
	#row_ooo,#row_start_time{margin-left:0}
	#row_deadline_time_s{margin-left:20px}
	/*
	  border: 1px solid #CCC;
  padding: .25rem;
  box-sizing: border-box;
  background-color: #FFF;
  margin: 0;
  margin-top: -1px;
  position: relative;
  border-top: 0;
	*/
	</style>
	<div class="flex fix">
		<!--
		<?=Inp('progress','進度<span id="zxcccczq" style="display:inline-block;width:3rem;text-align:right;"></span>','<input type="range" style="width:100%" value="0" min="0" max="100" step="20"/>',false)?>
		-->
		<?
		$adb=[];
		foreach(range(0,100,10) as $st){
			$adb[]='<option value="'.$st.'">'.$st.'%</option>';
		}
		echo Inp('progress','|工作進度','<select class="txt">'.implode('',$adb).'</select>');
		?>
		<script>
		$('#progress').bind('change input',function(){
			$('#zxcccczq').text(this.value+'%');
		});
		</script>
		
		<?
		$zx=[];
		foreach(range(1,24*5) as $zzc){
			$h=$zzc%24;
			$daa=floor($zzc/24);
			$show=($daa==0?'':$daa.'日').($h==0?'':$h.'時');
			$zx[]='<option value="'.$zzc.'">'.$show.'</option>';
		}
		$zx=implode('',$zx);
		echo Inp('spent','|花費時間','<select class="txt">'.$zx.'</select>',false);
		?>

		<?=Inp('evaluate','|執行狀態','<select class="txt" def="3"></select>',false)?>
	</div>
	<?=Inp('upload','上傳檔案','<div ></div>',false)?>
	<style>
	/*#row_finish_time,#row_end_time{
		width:12rem;
		flex:none;
	}*/
	/*
	#row_evaluate{width:112px;width:7rem;-webkit-box-flex:0;-webkit-flex:none;-ms-flex:none;flex:none}
	#row_spent{width:80px;width:5rem;-webkit-box-flex:0;-webkit-flex:none;-ms-flex:none;flex:none}
	*/
	#row_progress{-webkit-box-flex:2;-webkit-flex:2;-ms-flex:2;flex:2}
	</style>
	
	
	<div id="detail" class="hide">	
		<?=Inp('description','描述','<textarea class="txt" rows="4"></textarea>',false)?>
		<?
			//$respList=Project::getPartner($pid);
			echo Inp('resp_id','負責人','<select class="txt" def="'.Session::get('id').'"></select>',false);
		?>
		<?=Inp('start_time','提出日期','<input type="text" class="datetime" value="'.getTime(null,'html').'"/>',false)?>

		<script>
		(function(){
			var list=$("#resp_id");
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
			list.val(list.attr('def'));
			list.removeAttr('def');
		})();
		</script>
	<?=Inp('status','值行情況說明','<textarea class="txt" rows="4"></textarea>',false)?>

		



	</div>
		
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
		<?=Inp('priority','優先順序','<select class="txt" def="3"></select>',false)?>
		
		<?=Inp('title','標題','<input type="text"/>')?>
		
		<?
		$pop=['<option value="-1">[請選擇]</option>'];
		$gid=Project::getGroupPid(Session::get('id'));
		$ginfo=Project::getNames($gid);
		foreach($ginfo as $k=>$v){
			$pop[]='<option value="'.$k.'">'.e($v).'</option>';
		}
		?>
		<?=Inp('project_id','專案','<select class="txt" def="'.$pid.'">'.implode('',$pop).'</select>',false)?>
	</div>
	<script>
	appendGetToSelect("#evaluate",getEval);
	appendGetToSelect("#priority",getPriority);
	</script>
	
	
	<?
	foreach($info->fields as $k=>$v){
		echo $v;
	}
	?>
	
	<div class="row c fix">
		<button class="bt" onclick="window.add_type='report'">送出</button>
		<a href="<?=$backUrl?>&back=<?=urlencode($backUrl)?>"><button class="" type="button">取消並返回上一頁</button></a>
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

<div class="row">
	<?
	if($id!=-1){
		echo Inp('uploadedFile','已上傳檔案','<div ></div>',false);
	}
	?>
	<script>
	function refFileList(){
		Ajax('File','getFiles',{
			tid:	<?=$id?>
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
	
</div>


<div class="row">
	<div id="board"></div>
	<label>留言</label>
		<form class="form" sc="postMsg">
			<textarea class="txt" id="msg" placeholder="請輸入文字" rows="3"></textarea>
			<div class="r">
				<button class="bt">留言</button>
			</div>
			
			<script>
			function postMsg(){
				var msg=$('#msg').val().trim();
				Ajax('Board','postBoard',{
					tid:	<?=$info->id?>,
					msg:	msg
				},function(d){
					if(d){
						$("#msg").val('');
						refBoard();
						msgbox('發表成功','success');
					}else{
						msgbox('發表失敗','error');
					}
				});
			}
			</script>
		</form>
		
		<script>
		function refBoard(){
			Ajax('Board','getBoard',{
				tid:	<?=$info->id?>
			},function(d){
				var board=$("#board").empty();
				var i=0;
				d.forEach(function(b){
					<?php
					if(isMobile()){echo 'if(i>=5) return;';}
					?>
					var bd='';
					var m=$('<pre class="msg"></pre>');

					with(b){
						m.text(msg);
						m=m[0].outerHTML;
						
						bd='\
							<div class="board">\
								<div class="name">'+USERS[uid]+'</div>\
								<div class="time">'+post_time+'</div>\
								'+m+'\
							</div>\
						';
					}
					
					board.append(bd);
					i++;
				});
			})
		}
		refBoard();
		</script>
</div>

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
	var data=getVal('id|project_id|project_no|show_id|title|own_id|resp_id|progress|status|description|start_time|end_time|finish_time|priority|score|evaluate|done|deadline_time|spent');
	getCustField(data);
	Ajax('Task','edit',data,function(d){
		if(d!==false){
			var add_type=window.add_type || 'report';
			switch(add_type){
				case "report":
					msgbox('回報完成','success',function(){
						location.href=backUrl+'&'+encodeURIComponent(backUrl);
					});
				break;
				case "new_new":
					location.href=location.href+'&back='+encodeURIComponent(backUrl);;
				break;
			}
		}
	});
}

function addAttach(){
	var fs=$("#upload input");
	var len=0;
	var count=0;
	function don(){
		refFileList();
	}
	fs.each(function(){
		len+=this.files.length;
		if(len==0){
			don();
		}else{
			for(var i=0;i<this.files.length;i++){
				uploadFile(this.files[i],{
					task_id:	<?=$info->id?>
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
}
</script>
<script>
var disarr="priority|title|project_id|resp_id|start_time".split('|').join(',#');
$("#"+disarr).prop('disabled',true);
</script>
<style>
.setNow{
	
}
</style>