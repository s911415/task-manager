<?
$id=intval($_GET['id']);
$info=Task::getTask($id);
$pid=intval($_GET['pid']);
?>
<script>
var tid=<?=$id?>;
TITLE(<?=json_encode($info->title)?>);
</script>
<div class="w">
	<form class="form">
		<?=Inp('sn','流水號','<input type="text" disabled/>')?>
		<?=Inp('show_id','編號','<input type="text" class="noact"/>')?>
		<?=Inp('title','標題','<input type="text" class="noact"/>')?>
		<?
			$respList=Project::getPartner($pid);
			echo Inp('resp_id','負責人','<select class="txt noact" def="'.$_SESSION['id'].'"></select>');
		?>
		<script>
		(function(){
			var rList=<?=json_encode($respList)?>;
			var list=$("#resp_id");
			rList.forEach(function(id){
				list.append(
					'<option value="'+id+'">'+USERS[id]+'</option>'
				);
			});
			list.val(list.attr('def'));
			list.removeAttr('def');
		})();
		</script>
		
		<?=Inp('progress','進度','<input class="txt noact" type="number" value="0" min="0" max="100"/>')?>
		<?=Inp('description','描述','<textarea class="txt noact" rows="4"></textarea>',false)?>
		<?=Inp('status','值行情況說明','<textarea class="txt noact" rows="4"></textarea>',false)?>
		<?=Inp('start_time','提出日期','<input type="text" class="datetime noact" value="'.getTime(time()+86400).'"/>')?>
		<?=Inp('deadline_time','期限','<input type="text" class="datetime noact" value="'.getTime().'"/>')?>
		<?=Inp('end_time','預計完成日期','<input type="text" class="datetime noact">','預計完成日期')?>
		<?=Inp('finish_time','實際完成日期','<input type="text" class="datetime noact"/>',false)?>
		<?
		$zx=[];
		foreach(range(1,24) as $zzc){
			$zx[]='<option value="'.$zzc.'">'.$zzc.'時</option>';
		}
		$zx=implode('',$zx);
		echo Inp('spent','花費時間','<select class="txt noact">'.$zx.'</select>',false);
		?>
		<?=Inp('evaluate','執行狀態','<select class="txt noact" def="3"></select>')?>
		<?
		$s=[];
		foreach($scores as $d){
			$s[]='<option>'.e($d).'</option>';
		}
		echo Inp(
			'score',
			'等第',
			'<select class="txt noact">'.implode('',$s).'</select>',
			false
		)?>
		<?=Inp('priority','優先順序','<select class="txt noact" def="3"></select>')?>
		<?=Inp('done','結案','<input type="checkbox" disabled/>',false)?>


		<?=Inp('uploadedFile','附件下載','<div ></div>',false)?>
		<div id="save" class="hide">
			<div style="display:flex">
				<button class="bt" style="flex:1;margin-right:10px">送出</button>
				<button type="button" style="flex:1;margin-left:10px" onclick="setEditMode(false)">取消</button>
			</div>
		</div>
		
	</form>
</div>
<div class="w" id="board">
	<form class="form" sc="sendMsg">	
		<?=Inp('msg','討論','<textarea class="txt" placeholder="請輸入文字" rows="3"></textarea>')?>
		<button class="bt fill">留言</button> 
	</form>
	
	<div id="boardList"></div>
	<script>
	function sendMsg(){
		var msg=$('#msg').val().trim();
		Ajax('Board','postBoard',{
			tid:	tid,
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
	
	function refBoard(){
		Ajax('Board','getBoard',{
			tid:	tid
		},function(d){
			var board=$("#boardList").empty();
			
			d.forEach(function(b){
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
			});
		})
	}
	refBoard();
	</script>
</div>

<div class="hide">
	<?=Inp('id','id','<input type="hidden" value="-1"/>')?>
	<?=Inp('project_id','project_id','<input type="hidden" value="'.$pid.'"/>')?>
	<?=Inp('own_id','own_id','<input type="hidden" value="'.$_SESSION['id'].'"/>')?>
</div>
<script>
appendGetToSelect("#evaluate",getEval);
appendGetToSelect("#priority",getPriority);

function refFileList(){
	Ajax('File','getFiles',{
		tid:	<?=$id?>
	},function(flist){
		var list=$("#uploadedFile").empty();
		flist.forEach(function(f){
			with(f){
				list.append('\
					<div class="uploadF" href="<?=mkLink('File','getFile',[
						'sp'	=>	1
					])?>&id='+id+'">\
						<div class="fn">'+filename+'</div>\
						<div class="row">\
							<div class="ud">'+upload_time+'</div>\
							<div class="un">'+USERS[owner]+'</div>\
							<div class="fs">'+minByte(filesize)+'</div>\
						</div>\
					</div>\
				');
			}
			
		});
		if(flist.length==0){
			$('#uploadedFile').parent().hide();
		}
		
		$('.uploadF').click(function(){
			window.open(this.getAttribute('href'));
		});
	});
}
refFileList();
</script>
<script>
(function(d){
	if(d==null){
		d={};
		d.sn=TaskId(
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
		d.own=<?=$_SESSION['id']?>;
	}else{
		with(d){
			d.sn=TaskId(project_id,project_no);
			d.own=USERS[own_id];
			d.resp=USERS[resp_id];
		}
	}
	
	passVal(d);
})(<?=json_encode($info)?>);
</script>


<script>

	
$('.taskDone').change(function(){
	Ajax('Task','setDone',{
		tid:	this.value,
		done:	this.checked?1:0
	},function(d){
		if(d){
			msgbox('操作完成','success');
		}else{
			msgbox('操作失敗','error')
		}
	});
});

function refFileList(){
	Ajax('File','getFiles',{
		tid:	<?=$id?>
	},function(flist){
		var list=$("#uploadedFile").empty();
		flist.forEach(function(f){
			with(f){
				list.append('\
					<div class="uploadF" href="<?=mkLink('File','getFile',[
						'sp'	=>	1
					])?>&id='+id+'">\
						<div class="fn">'+filename+'</div>\
						<div class="row">\
							<div class="ud">'+upload_time+'</div>\
							<div class="un">'+USERS[owner]+'</div>\
							<div class="fs">'+minByte(filesize)+'</div>\
						</div>\
					</div>\
				');
			}
			
		});
		
		$('.uploadF').click(function(){
			window.open(this.getAttribute('href'));
		});
	});
}
refFileList();
</script>
<script>
function fcb(){
	var data=getVal('id|project_id|show_id|title|own_id|resp_id|progress|status|description|start_time|end_time|finish_time|priority|score|evaluate|done|deadline_time|spent');
	
	pTs(data,'start_time|end_time|finish_time|deadline_time');
	
	Ajax('Task','edit',data,function(d){
		if(d){
			msgbox('操作完成');
			location.change({
				'id':	d
			});
		}else{
			msgbox('系統錯誤','error');
		}
	});
}
</script>
<script>
var f=<?=json_encode(getVal($_GET,'from'))?>;
function setEditMode(enable){
	if(enable){
		if(f!='manage'){
			$('#show_id,#title,#resp_id,#start_time,#evaluate,#score,#description,#priority,#done,#deadline_time,#end_time').prop('disabled',true);
		}
		
		$('.noact').addClass('yesact').removeClass('noact');
		$('#board').hide();
		$('#save').show();
	}else{
		$('.yesact').addClass('noact').removeClass('yesact');
		$('#board').show();
		$('#save').hide();
	}
}

setEditMode(<?=json_encode(isset($_GET['edit']))?>);
</script>

<script>
addIcon('mobile/edit.png',function(){setEditMode(true)});
f=='manage' && addIcon('mobile/track.png',function(){
	//'./index.php?act=User&func=select&type=checkbox&target=partner&ispop=1#' + partner,
	var w=window.open(
		'about:blank',
		'selectPartner',
		'width=640,height=300'
	);
	Ajax('Track','getTrackList',{
		tid:	tid
	},function(d){
		var rndId="";
		while(rndId.length<15){
			rndId+=String.fromCharCode(Math.floor(Math.random()*26)+97);
		}
		rndId+=new Date().getTime();
		
		var inp=$('<input type="hidden" id="'+rndId+'"/>');
		inp.change(function(){
			Ajax('Track','setTrackList',{
				tid:	tid,
				uids:	this.value
			},function(d){
				if(d){
					msgbox('修改完成','success',function(){
						inp.remove();
					});
				}
			});
		});
		
		$('body').append(inp);
		
		w.location.replace("<?=mkLink('User','select',[
			'type'	=>	'checkbox',
			'ispop'	=>	1
		])?>&target="+rndId+'#'+d.join(','));
		w.focus();
	});
})
</script>