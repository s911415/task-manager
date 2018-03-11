<?
$list=Tel::getList();
?>
<script>

var school=getTelCity();
function getSchool(id){
	var s=school.all[id];
	return [
		s.city,
		s.area,
		s.schoolname,
	].join('/');
}

</script>
<script>
(function(){
var cate=getTelCategory();

window.getCateName=function(id){
	var b=cate.all[id];
	return [
		b.big_cate,b.value
	].join('/');
};

})();
</script>
<table border="1" width="100%" style="border-collapse:collapse;" id="telExport">
<thead>
<tr>
	<th>編號</th>
	<th>客服人員</th>
	<th>來電時間</th>
	<th>姓名</th>
	<th>電話</th>
	<th>學校</th>
	<th>問題描述</th>
	<th>處理方式</th>
	<th>備註</th>
	<th>類別</th>
	<th>協助人員</th>
	<th>檢核人</th>
	<th>處理時間</th>
	<th>是否完成</th>
</tr>
</thead>
<tbody>
<?
foreach($list as $t){
?>
<tr>
	<td><?=$t->no?></td>
	<td><?=showUserName($t->task_uid)?></td>
	<td><?=$t->incall_time?></td>
	<td><?=$t->tea_name?></td>
	<td><?=$t->tea_phone?></td>
	<td><?="<script>document.write(getSchool(".$t->school_id."))</script>"?></td>
	<td><?=($t->problem)?></td>
	<td><?=($t->handle)?></td>
	<td><?=($t->note)?></td>
	<td><?="<script>document.write(getCateName('".$t->cate_id."'))</script>"?></td>
	<td><?=showUserName($t->help_uid)?></td>
	<td><?=showUserName($t->check_uid)?></td>
	<td><?=$t->cost?></td>
	<td><?=($t->finish?'是':'否')?></td>
</tr>
<?
}
?>
</tbody>
</table>
<script>
(function(){
	var html='<!DOCTYPE HTML><html><head><meta charset="UTF-8"/></head><body>'+$("#telExport")[0].outerHTML+'</body></html>';
	var blob=new Blob([html], {type:"application/octect-stream"});
	var url=URL.createObjectURL(blob);
	var link=document.createElement('a');
	link.href=url;
	link.download="客服紀錄(<?=date('Y-m-d H_i_s')?>).xls";
	link.click();
})();
</script>
<?
die;