<?
$t=Tel::getInfo(Input::get('id'));
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
<div class="record">
	<div class="flex">
		<?=Inp('no','|編號',"<span class='ttt'>".($t->no)."</span>",false)?>
		<?=Inp('task_uid','|客服人員',"<span class='ttt'>".showUserName($t->task_uid)."</span>",false)?>
		<?=Inp('incall_time','|來電時間',"<span class='ttt' >".$t->incall_time."</span>",false)?>
		
	</div>
	<div class="flex">
		<?=Inp('tea_name','|姓名',"<span class='ttt'>".$t->tea_name."</span>",false)?>
		<?=Inp('tel_phone','|電話',"<span class='ttt'>".$t->tea_phone."</span>",false)?>
		<?=Inp('school','|學校',"<span class='ttt'><script>document.write(getSchool('".$t->school_id."'))</script></span>",false)?>
	</div>
	<div class="flex">
		<?=Inp('program','|問題描述',"<span class='ttt' >".($t->problem)."</span>",false)?>
		<?=Inp('handle','|處理方式',"<span class='ttt' >".($t->handle)."</span>",false)?>
	</div>
	
	<div class="flex">
		<?=Inp('cate','|類別',"<span class='ttt'><script>document.write(getCateName('".$t->cate_id."'))</script></span>",false)?>
		<?=Inp('help_uid','|協助人員',"<span class='ttt' >".showUserName($t->help_uid)."</span>",false)?>
	</div>

	<div class="flex">
		<?=Inp('upload_area', '|附檔', '<div ></div>', false)?>
	<!--</div>
	<div class="flex">-->
		<?=Inp('note','|備註',"<span class=''>".($t->note)."</span>",false)?>
	</div>
	<div class="flex">
		<?=Inp('check_uid','|檢核人',"<span class='ttt' >".showUserName($t->check_uid)."</span>",false)?>
		<?=Inp('cost','|處理時間','<span class="ttt">'.$t->cost.'</span>',false)?>
		<?=Inp('finish','|處理情形',"<span class='ttt' >".($t->finish?'已完成':'待處理')."</span>",false)?>
	</div>
	<div class="flex" style="border-bottom:none">
		<div class="row" style="flex:3"></div>
		<?=Inp('actions_asd','| ','<span >
			<a href="#" onclick="expTask()" id="soijdgj">轉入工作管理系統</a> <br/>
			<a href="'.telPage('edit').'&id='.$t->id.'">編輯</a> &nbsp;
			<a href="'.telPage('del').'&id='.$t->id.'">刪除</a> &nbsp;
			<a href="'.(@$_SERVER['HTTP_REFERER']?:telPage('list')).'"><u>返回</u></a>
		</span>' ,false)?>
		<style>#actions_asd{min-width:310px}</style>
	<!--
		<?
		if(true || Session::get('id')===$t->check_uid){
		?>
		
		<?=Inp('actions_qwe','| ','<span >
			<a href="#" onclick="expTask()" id="soijdgj">轉入工作管理系統</a> <br/>
			<a href="'.telPage('edit').'&id='.$t->id.'">編輯</a> &nbsp;
			<a href="'.telPage('del').'&id='.$t->id.'">刪除</a> &nbsp;
			<a href="'.(@$_SERVER['HTTP_REFERER']?:telPage('list')).'"><u>返回</u></a>
		</span>' ,false)?>
		
		<div class="row" style="flex:2"></div>
		<?
		}else{
		?>
		<div class="row" style="flex:3"></div>
		<?
		}
		?>
		<div class="row" style="flex:2"></div>
		<?=Inp('actions_asd','| ','<span >
			<a href="#" onclick="expTask()" id="soijdgj">轉入工作管理系統</a> <br/>
			<a href="'.telPage('edit').'&id='.$t->id.'">編輯</a> &nbsp;
			<a href="'.telPage('del').'&id='.$t->id.'">刪除</a> &nbsp;
			<a href="'.(@$_SERVER['HTTP_REFERER']?:telPage('list')).'"><u>返回</u></a>
		</span>' ,false)?>
	-->
	</div>
</div>
<?
if($t->task_id>0){
?>
<style>
#soijdgj,#soijdgj~br{display:none;}
</style>
<?
}
?>
<script>

function expTask(){
	$('#soidgj form').submit();
}
</script>
<div id="soidgj" hidden>
<form target="_blank" action="<?=mkLink('Task','add',[
	'pid'	=>	38,
	'telPage'=>	'a'
])?>" method="POST">
	<input type="hidden" value="客服回報 - <?=$t->no?>" name='passFromTel[title]'/>
	<input type="hidden" value="<?=$t->id?>" name='passFromTel[tel_id]'/>
	
	<textarea name="passFromTel[description]">Q:<?=$t->program?>
A:<?=$t->handle?>

備註:<?=$t->note?></textarea>
</form>
</div>
<script>

	function refFileList(){
		Ajax('File','getFiles',{
			tid:	<?=$t->id?>,
			pid:	-2
		},function(flist){
			var list=$("#upload_area").empty();
			flist.forEach(function(f){
				with(f){
					list.append('\
					<div class="uploadF" href="<?=mkLink('File','getFile',[
							'sp'	=>	1
						])?>&id='+id+'">\
						<div class="fn">'+filename+'</div>\
						<div class="row">\
							<!--<div class="ud">'+upload_time+'</div>-->\
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