<?
$id=intval($_GET['id']);
$info=Meeting::getMeeting($id);
?>
<h2 class="title"><?=e($info->name)?> 會議記錄</h2>

<table class="table table2">
<tr>
	<td class="head">會議名稱</td>
	<td class="cont"><?=$info->name?></td>

	<td class="head">主持人</td>
	<td class="cont">
		<script>
		document.write(USERS[<?=$info->holder?>])
		</script>
	</td>
</tr>
<tr>
	<td class="head">參與人</td>
	<td class="cont" colspan="3">
	<script>
	(function(){
		var ids=<?=json_encode($info->join_uid)?>.split(",");
		var names=[];
		ids.forEach(function(id){
			names.push(USERS[id]);
		});
		document.write(names.join('、'));
	})();
	</script>
	</td>
</tr>
<tr>
	<td class="head">會議開始時間</td>
	<td class="cont"><?=$info->start_time?></td>

	<td class="head">會議結束時間</td>
	<td class="cont"><?=$info->end_time?></td>
</tr>
<tr>
	<td class="head">
		會議記錄
		<br/>
		<a href="<?=mkLink('Meeting','getLog',[
			'id'	=>	$id,
			'sp'	=>	1
		])?>" target="_blank">下載</a>
	</td>
	<td class="cont" colspan="3">
		<pre><?=e($info->commentLog)?></pre>
	</td>
</tr>
</table>