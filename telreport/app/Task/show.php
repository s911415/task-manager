<?
$id=intval(Input::get('tid'));
$info=Task::getTask($id);
$backUrl=Input::get('back',Server::get('HTTP_REFERER','./'));

?>
<script>
var tid=<?=$id?>;
</script>
<a href="<?=$backUrl?>">
	<button class="bt right" style="margin-top:1rem">返回</button>
</a>
<h2 class="title"><span style="color:blue"><?=e($info->title)?></span> - 工作資訊</h2>

<table class="table table2" style="  table-layout: fixed;background:#FFF">
<!--
	<tr>
		<td class="head">
			流水號
		</td>
		<td class="cont">
			<script>document.write(TaskId(<?=$info->project_id?>,<?=$info->project_no?>))</script>
		</td>
		<td class="head">編號</td>
		<td class="cont"><?=$info->show_id?></td>
	</tr>
-->
	<tr>
		<td class="head">專案名稱</td>
		<td class="cont"><?=e($info->project_name)?></td>
		<td class="head">提出者</td>
		<td>
			<script>document.write(USERS[<?=$info->own_id?>])</script>
		</td>
	</tr>
	<tr>
		<td class="head">負責者</td>
		<td>
			<script>document.write(USERS[<?=$info->resp_id?>])</script>
		</td>
		<td class="head">優先順序</td>
		<td>
			<script>document.write(getPriority(<?=$info->priority?>))</script>
		</td>
	</tr>
	
	<tr>
		<td class="head">描述</td>
		<td colspan="3">
			<pre><?=e($info->description)?></pre>
		</td>
	</tr>
	<tr>
		<td class="head">值行情況說明</td>
		<td colspan="3">
			<pre><?=e($info->status)?></pre>
		</td>
	</tr>
	
	<tr>
		<td class="head">提出日期</td>
		<td>
			<?=substr($info->start_time,0,16)?>
		</td>
		<td class="head">期限</td>
		<td><?=substr($info->deadline_time,0,16)?></td>
	</tr>
	
	<tr>
		<td class="head">預計完成日期</td>
		<td>
			<?=Inp('end_time','','<input type="text" class="datetime" value="'.getTime($info->end_time,'html').'">',false)?>
		</td>
		<td class="head">實際完成日期</td>
		<td>
			<?=Inp('finish_time','','<input type="text" class="datetime" value="'.getTime($info->finish_time,'html').'">',false)?>
			
			<script>
			$('#finish_time,#end_time').change(function(){
				var Data={
					tid:	tid,
					score:	this.value
				};
				Ajax('Task','setScore',Data,function(d){
					if(d){
						msgbox('更新完成','success');
					}
				});
			});
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="head">
			花費時間
		</td>
		<td colspan="3">
			<?=$info->spent?>時
		</td>
	</tr>
	
	<tr>
		<td class="head">執行狀態</td>
		<td>
			<select class="txt" id="evaluate" def="<?=$info->evaluate?>"></select>
			<script>
			appendGetToSelect("#evaluate",getEval);
			$("#evaluate")
			.bind('change',function(){
				var Data={
					tid:	tid,
					evaluate:	this.value
				};
				Ajax('Task','setEval',Data,function(d){
					if(d){
						msgbox('更新完成','success');
					}
				});
			});
			</script>
			<!--
			<input type="text" value="<?=e($info->evaluate)?>" id="evaluate"/>
			<script>
			
			</script>
			-->
		</td>
		<td class="head">等第</td>
		<td>
			<?
			$s=[];
			foreach($scores as $d){
				$s[]='<option>'.e($d).'</option>';
			}
			?>
			<select id="score" class="txt"><?=implode('',$s)?></select>
			<script>
			$("#score")
			.val(<?=json_encode($info->score)?>)
			.bind('change',function(){
				var Data={
					tid:	tid,
					score:	this.value
				};
				Ajax('Task','setScore',Data,function(d){
					if(d){
						msgbox('更新完成','success');
					}
				});
			});
			
			
			</script>
		</td>
	</tr>
	<tr>
		<td class="head">進度</td>
		<td>
			<select id="progress" def="<?=$info->progress?>" class="txt">
				<?
				foreach(range(0,100,10) as $r){
					echo '<option value="'.$r.'">'.$r.'%</option>';
				}
				?>
			</select>
			<script>
			$("#progress")
			.bind('change',function(){
				var Data={
					tid:	tid,
					progress:	this.value
				};
				Ajax('Task','setProgress',Data,function(d){
					if(d){
						msgbox('更新完成','success');
					}
				});
			});
			</script>
		</td>
		<td class="head">是否結案</td>
		<td>
			<input type="checkbox" value="<?=$id?>" class="taskDone" <?=$info->done==1?'checked':''?>/>
		</td>
	</tr>
	
	<?
	foreach($info->fie_original as $k=>$v){
	?>
	<tr>
		<td class="head">
			<?=$k?>
		</td>
		<td colspan="3">
			<?=str_replace('|','、',$v)?>
		</td>
	</tr>
	<?	
	}
	?>
	
</table>
<?=Inp('uploadedFile','附件下載','<div ></div>',false)?>

<div class="row clear">
	<label>討論</label>
	<form class="form">
		<textarea class="txt" id="msg" placeholder="請輸入文字" rows="3"></textarea>
		<div class="r">
			<button class="bt">留言</button>
		</div>
		
		<script>
		function fcb(){
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
		</script>
	</form>
	
	<hr/>
	
	<div id="board"></div>
	<script>
	function refBoard(){
		Ajax('Board','getBoard',{
			tid:	tid
		},function(d){
			var board=$("#board").empty();
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
<?
if(getVal($_GET,'from')!='manage'){
?>
<script>
$('#evaluate,#score,.taskDone').prop('disabled',true);
</script>
<style>.delF{display:none}</style>
<?
}
?>