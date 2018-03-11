<?
	$id=intval($_GET['id']);
	$pid=intval($_GET['pid']);
	$method='修改';
	$info=Task::getTask($id);
	if($id==-1){
		$method='新增';
	}
?>
<h2 class="title">
	<?=$method?>工作
	<script>
	document.write(
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
		)
	);
	</script>
</h2>
<form class="form">
	<div class="two fix">
	</div>
	<div class="two fix">

		
	</div>
	<?
	if($id!=-1){
		echo Inp('uploadedFile','已上傳檔案','<div ></div>');
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
	<?=Inp('upload','上傳檔案','<div ></div>',false)?>
	<script>
	
	</script>
	<div class="hide">
		<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
		<?=Inp('project_id','project_id','<input type="hidden" value="'.$pid.'"/>')?>
		<?=Inp('project_no','project_no','<input type="hidden" value="'.$project_no.'"/>')?>
		<?=Inp('own_id','own_id','<input type="hidden" value="'.$_SESSION['id'].'"/>')?>
	</div>
	<script>
	appendGetToSelect("#evaluate",getEval);
	appendGetToSelect("#priority",getPriority);
	</script>
	
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
	var data=getVal('id|project_id|project_no|show_id|title|own_id|resp_id|progress|status|description|start_time|end_time|finish_time|priority|score|evaluate|done|deadline_time|spent');
	Ajax('Task','edit',data,function(d){
		if(d!==false){
			var fs=$("#upload input");
			var len=0;
			var count=0;
			function don(){
				var msg='<?=$method?>完成';
				msg+='<div style="">';
					msg+='<a href="<?=mkLink('Project','manage')?>"><button>回專案管理</button></a>';
					if(<?=json_encode(getVal($_GET,'from',''))?>=='manage'){
						msg+='<a href="<?=mkLink('Task','manage',['pid'=>$pid])?>"><button class="bt">回工作管理</button></a>';
					}else{
						msg+='<a href="<?=mkLink('Task','List')?>"><button class="bt">回工作清單</button></a>';
					}
					
					if(data.id==-1){
						msg+='<a href="<?=mkLink('Task','edit',['pid'=>$pid,'id'=>-1,'from'=>'manage'])?>"><button>新增工作</button></a>';
					}else{
						msg+='<a href="'+location.href+'"><button>繼續編輯</button></a>';
					}
				msg+='</div>';
				msgbox(msg,'success',function(){
					/*
					if(data.id==-1){
						location.href='<?=mkLink('Task','edit',[
							'pid'	=>	$pid,
							'from'=>	getVal($_GET,'from')
						]);?>&id='+d;
					}else{
						location.reload();
					}
					*/
					location.href=$("#msgbox button.bt").parent().attr('href');
					
				});
			}
			fs.each(function(){
				len+=this.files.length;
				if(len==0){
					don();
				}else{
					for(var i=0;i<this.files.length;i++){
						uploadFile(this.files[i],{
							task_id:	d
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
<?
if(getVal($_GET,'from')!='manage'){
?>
<script>
$('#show_id,#title,#resp_id,#start_time,#evaluate,#score,#description,#priority,#done,#deadline_time,#end_time').prop('disabled',true);
</script>
<style>.delF{display:none}</style>
<?
}
?>