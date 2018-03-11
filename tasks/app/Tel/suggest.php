<?php
$oldData=Input::get("oldData", []);
if(($id=Input::get('cid', 1)) == -1){
	TelSuggest::edit($oldData[-1]);
}else if(($id=Input::get('uid', -1))>0){
	TelSuggest::edit($oldData[$id]);
}else if(($id=Input::get('uid', -1))>0){
	TelSuggest::del($id);
}
?>
<script>
(function(){

var cate=window.cate=getTelCategory();

window.getCateName=function(id){
	var b=cate.all[id];
	return [
		b.big_cate,b.value
	].join('/');
};
})();
</script>
<style>
.table {
	font-size: 14px;
	font-family: '新細名體';
}
.oidfhjo td{
	vertical-align: top;
}
.oidfhjo button{
	font-size:12px;
	line-height: 20px;
	height: 32px;
	padding: 3px 5px;
}
</style>
<form class="form" method="POST">
	<table class="table" style="table-layout: fixed">
		<thead>
		<tr>
			<th width="50">#</th>
			<th width="150">簡短關鍵字</th>
			<th>問題描述</th>
			<th>處理方式</th>
			<th width="80">選取頻率</th>
			<th width="130"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$list=TelSuggest::getList(['form'=>'manage']);
		$list[]=TelSuggest::getNew();
		foreach($list as $d){
			$inputId="oldData[{$d->id}]";
		?>
		<tr class="oidfhjo">
			<td align="right"><?=$d->id<0?'N/A':$d->id?><input type="hidden" name="<?=$inputId.'[id]'?>" value="<?=$d->id?>"/></td>
			<td><input type="text" name="<?=$inputId.'[keyword]'?>" value="<?=e($d->keyword)?>"/></td>
			<td><textarea class="txt" name="<?=$inputId.'[problem]'?>"><?=e($d->problem)?></textarea></td>
			<td><textarea class="txt" name="<?=$inputId.'[handle]'?>"><?=e($d->handle)?></textarea></td>
			<td><input class="txt" type="number" min="0" name="<?=$inputId.'[feq]'?>" value="<?=e($d->feq)?>"/></td>
			<td>
				<?php
				if($d->id<0){
				?>
					<button type="submit" name="cid" value="<?=$d->id?>" class="bt">新增</button>
				<?php
				}else{
				?>
					<button type="submit" name="uid" value="<?=$d->id?>" class="bt">更新</button>
					<button type="submit" name="did" value="<?=$d->id?>">刪除</button>
				<?php
				}
				?>
			</td>
		</tr>
		<?php
		}
		?>
		</tbody>
	</table>
</form>

<script>
(function(){
	cate.forEach(function(c){
		$('#mother').append('<option value="'+c.cate+'">'+c.cate+'</option>');
	});
	
	$('#mother').change(
	function(){
		var i=this.value;
		var childs=cate.getChild(i);
		
		var ch=$('#child').empty();
		childs.forEach(function(dfh){
			ch.append('<option value="'+dfh.id+'">'+dfh.value+'</option>');
		});
	}
	);
	$('#mother').change();
	
	window.getCateName=function(id){
		var b=cate.all[id];
		return [
			b.big_cate,b.value
		].join('/');
	};
})();
(function(){
	school.getAllZone().forEach(function(z){
		$('#zone').append('<option value="'+z+'">'+z+'</option>');
	});
	$('#zone').change(function(){
		var z=this.value;
		var ar=$('#area').empty();
		
		/*
		school.getAreaByZone(z).forEach(function(a){
			var aa=a.split('-');
			ar.append('<option value="'+aa[0]+'">'+aa[1]+'</option>');
		});
		ar.change();
		*/
		var sc=$('#school_id').empty();
		sc.find('~.custom-combobox>input').val('');

		school.getSchoolByZone(z).forEach(function(s){
			var vv=s.area_no + s.area+'/'+s.schoolname;
			
			sc.append('<option value="'+s.id+'">'+vv+'</option>');
		});
	});
	/*
	$('#area').change(function(){
		var sc=$('#school_id').empty();
		school.getSchoolByArea(this.value).forEach(function(w){
			sc.append('<option value="'+w.id+'">'+w.schoolname+'</option>');
		});
	});
	*/
	$('#zone').change();
})();
for(var i in USERS){
	$('#task_uid,#check_uid,#help_uid').append('<option value="'+i+'">'+USERS[i]+'</option>');
}

(function(zxc){
zxc.incall_time=zxc.incall_time.replace(' ','T');
var c=cate.all[zxc.cate_id];
var s=school.all[zxc.school_id];
$('#mother').val(c.big_cate).change();
$('#zone').val(s.city).change();
$('#area').val(s.area_no).change();
zxc.done=0;
passVal(zxc);


})(<?=json_encode($t)?>);

</script>
<script>
function fcb(){
	var data=getVal('id|tea_name|tea_phone|school_id|problem|handle|note|cate_id|incall_time|finish|help_uid|check_uid|task_uid|cost|done|service')
	data.incall_time=data.incall_time.replace('T',' ');
	Ajax('Tel','edit',data,function(d){
		location.href=<?=json_encode(telPage('list'))?>;
		d && msgbox('操作完成','success',function(){
		});
	});
}

$('#row_note').addClass('spp');

$('#school_id').combobox();
</script>
<style>
#school #zone{width:110px}
#school .custom-combobox{flex:1}
</style>