<h2 hidden>客服紀錄查詢</h2>
<style>
#row_range{
	min-width:480px;
}
#row_range .hideSec{display:none !important}
#more.show{max-height:20rem}
#aaa,#bbb{
	width:calc(50% - 0.85rem) !important;
}
.row.title{margin-right:.75rem}
</style>
<script>
	$('.wh[txt="查詢"]').addClass('sel');
</script>
<div id="fpo" hidden>
	<div id="filter2" class="row">
<!--
		<div id="sfdh" class="wh wh2" wh="finish=0" style="background:#FF2D55">
			<div class="whic three_dead"></div>
			尚未完成
		</div>
		<div id="oidfj" class="wh wh2" wh="1" style="background-color:#4CD964">
			<div class="whic new_add"></div>本日新增
		</div>
-->
	</div>
	<script>
	var inaa=$('.wh[txt="返回"]');
	$('.wh2').each(function(i){
		$(this).insertBefore(inaa).attr('sg',i);
		
	});
	<?
	$kiufg=Input::get('kiufg',null);
	if($kiufg!==null){
		echo '$(".wh2[sg=\"'.$kiufg.'\"]").addClass("sel");';
	}
	?>
	$('.wh2').click(function(){
		if(this.id=='oijdfjh') return;
		var t=$(this).attr('wh');
		$('#oth').val(t);
		$('#kiufg').val($(this).attr('sg'));
		$('#aaa').val('1900-01-01');$('#bbb').val('2100-01-01');
		setTimeout(function(){$('#oijdfho').submit();},10);
	});
	$('#oidfj').click(function(e){
		var n=new Date();
		var now=[
			n.getFullYear(),
			(n.getMonth()+1).padLeft(2,'0'),
			n.getDate().padLeft(2,'0')
		].join('-');
		e.preventDefault();
		$('#aaa,#bbb').val(now);//$('#oijdfho').submit();
	});
	$('#oijdfjh').click(function(){
		location.href=<?=json_encode(telPage('edit'))?>;
	});
	</script>
	
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

</div>

<script>
$(function(){
	var ii=1,mm=location.hash.match(/^#wh(\d+)/);
	if(mm){
		ii=mm[1]*1+1;
	}
	/*$('.wh:nth-child('+ii+')').click();*/
});

</script>
<style>
.soihoifdhj{
    font-size: 16px;
    line-height: 1.25;
    padding: 5px;
    height: 2em;
    position: absolute;
    right: 0;
}
</style>
<div id="more">
<form action="<?=telPage('list')?>" method="GET" id="oijdfho">
<input type="hidden" name="act" value="Tel"/>
<input type="hidden" name="func" value="index"/>
<input type="hidden" name="telPage" value="list"/>
<input type="hidden" name="oth" id="oth" value="1"/>
<input type="hidden" name="kiufg" id="kiufg" value="0"/>
	<div class="flex">
		<?
		$d1=@Input::get('wh')['start_time']?:date('Y-m-d',strtotime('today')-86400*5);
		$d2=@Input::get('wh')['end_time']?:date('Y-m-d',strtotime('today'));
		echo Inp('range','|來電日期','
			<span ></span>
			<input id="aaa" type="date" value="'.$d1.'" name="wh[start_time]"/>
			 ~
			<input id="bbb" type="date" value="'.$d2.'" name="wh[end_time]"/>
		',false);
		?>
		<?=Inp('wh[cate_id]','|問題類別','<div >
		<select id="mother" class="txt">
			<option value="">不指定</option>
		</select>
		<select id="child" class="txt" name="wh[cate_id]"></select>
	</div>
	<button type="button" id="expCSV" class="soihoifdhj" style="
    transform: translate(-200%, -125%);
			">匯出CSV</button>
	
			<button type="submit" class="bt soihoifdhj" style="
	transform: translate(-25%, -125%);
			">搜尋</button>
	',false);?>
	
			
	<input type="hidden" name="dofih" value="ojih"/>
	</div>
	<style>
		.flex>.row[id="row_wh[task_uid]"], .flex>.row[id="row_wh[finish]"]{
			flex: .5;
		}
	</style>
	<div class="flex">
	
		<?=Inp('wh[task_uid]','|客服人員','<select class="txt"><option value="-1">不指定</option></select>',false)?>
		<?=Inp('wh[finish]','|處理情形','<select class="txt">
			<option value="-1">不指定</option>
			<option value="0">待處理</option>
			<option value="1">已完成</option>
		</select>',false)?>
		<?=Inp('wh[tea_phone]','|教師電話','<input type="text"/>',false)?>
		<?=Inp('wh[sch_name]','|單位名稱','<input type="text"/>',false)?>
		<?=Inp('wh[tea_name]','|教師姓名','<input type="text"/>',false)?>
		<script>
		(function(){
			for(var uid in USERS){
				$('select[id="wh[task_uid]"]').append('<option value="'+uid+'">'+USERS[uid]+'</option>');
			}
		})();
		</script>
	</div>

	<script>
	(function(){
		var cate=getTelCategory();
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
	</script>
</form>
</div>

	<div class="row clear">
		<a href="#" onclick="return showMore()" id="smore">Show More</a>
		<a href="#" onclick="return hideMore()" id="sless" style="">Show Less</a>
	</div>

<script>
if(<?=json_encode(Input::get('dofih')!='ojih')?>){
	//$('#oijdfho').submit();
}
</script>
<script>
var school=getTelCity();
function getSchool(id){
	var s=school.all[id];
	return [
		s.zone,
		s.area,
		s.schoolname,
	].join('/');
}
</script>

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
.row.resp{display:block}
</style>

<div id="headdd">
	<div class="task">
		<div class="row project_name">編號</div>
		<div class="row resp">客服人員</div>
		<div class="row title">問題描述 - 處理方式</div>
		<div class="row resp">教師姓名</div>
		<!--
		<div class="row action">
			功能&nbsp;
		</div>
		-->
	</div>
</div>

<div id="list">
<?
if(1 || Input::get('dofih')=='ojih'){
	$list=Tel::getList(@Input::get('wh',[]));
	foreach($list as $t){
	?>

<div class="task" data-tid="<?=$t->id?>" id="tel_<?=$t->id?>">
	<div class="row project_name"><?=$t->no?></div>
	<div class="row resp"><?=showUserName($t->task_uid)?></div>
	<div class="row title"><?=$t->problem?>
		<div class="descript">
			<?=$t->handle?>
		</div>
	</div>
	
	<div class="row resp rep2" data-sid="<?=$t->school_id?>" title="電話:<?=$t->tea_phone?> 
單位名稱:[asdasd] 
備註:<?=$t->note?> 
 
"><?=$t->tea_name?></div>
	<!--
	<div class="row action">
		<div class="act_icon edit" title="編輯" allow="1"></div>
	</div>
	-->
</div>
	<?
	}
	
	?>
	<script>
	(function(data){
		//#,時間,編號,客服人員,學校,姓名,問題描述,處理方式,備註,類別
		function escapeCSV(data){
			if(data.toString()!=data || typeof data=='object'){
				for(var k in data){
					data[k]=escapeCSV(data[k]);
				}
				
				return data;
			}else{
				data=data.toString();
				return '"'+
				data.replace(/"/g,'""')
				+'"';
			}
		}
		var exportData=[];
		exportData.push("#,時間,編號,客服人員,學校,姓名,問題描述,處理方式,備註,類別".split(","));
		data.forEach(function(d){
			with(d){
				exportData.push([
					id,
					incall_time,
					no,
					USERS[task_uid],
					getSchool(school_id),
					tea_name,
					problem,
					handle,
					note,
					getCateName(cate_id)
				]);	
			}
			
		});
		exportData=escapeCSV(exportData);
		
		var csvDocument=[];
		exportData.forEach(function(row){
			csvDocument.push(row.join(','));
		});
		
		csvDocument=csvDocument.join('\r\n');
		var b=new Blob([csvDocument],{type:'text/csv'});
		var csvUrl=URL.createObjectURL(b);
		
		$('#expCSV').click(function(){
			window.open(csvUrl);
		});
	})(<?=json_encode($list)?>);
	</script>
	<?
}
?>
<script>
$('.rep2').each(function(){
	this.title=this.title.replace(/\[asdasd\]/,getSchool($(this).data('sid')))
});
$('.edit').click(function(e){
	var tid=$(this).parents('.task').data('tid');
	location.href=<?=json_encode(telPage('edit').'&id=')?>+tid;
});
$('.task').click(function(e){
	if(e.target.classList.contains('icon')) return;
	var tid=$(this).data('tid');
	location.href=<?=json_encode(telPage('show').'&id=')?>+tid;
});
showMore()
</script>
<div>
<?
/*
if(Input::get('dofih')=='ojih'){
foreach(Tel::getList(@$_POST['wh']?:[]) as $t){
	
?>
<div class="record">
	<div class="flex">
		<?=Inp('id','|編號',"<span class='ttt'>".($t->no)."</span>",false)?>
		<?=Inp('cate','|分類',"<span class='ttt'><script>document.write(getCateName('".$t->cate_id."'))</script></span>",false)?>
	</div>
	<div class="flex">
		<?=Inp('tea_name','|姓名',"<span class='ttt'>".$t->tea_name."</span>",false)?>
		<?=Inp('tel_phone','|電話',"<span class='ttt'>".$t->tea_phone."</span>",false)?>
		<!--<?=Inp('school','|學校',"<span class='ttt'><script>document.write(getSchool(".'1'."))</script></span>",false)?>-->
	</div>
	<div class="flex">
		<?=Inp('program','|問題描述',"<span class='ttt' >".mb_substr($t->problem,0,25)."...</span>",false)?>
		<?=Inp('handle','|處理方式',"<span class='ttt' >".mb_substr($t->handle,0,25)."...</span>",false)?>
	</div>
	<?=Inp('description','|備註',"<span class=''>".mb_substr($t->note,0,25)."</span>",false)?>
	
	<div class="flex">
		<?=Inp('incall_time','|來電時間',"<span class='ttt' >".$t->incall_time."</span>",false)?>
		<?=Inp('help_uid','|協助人員',"<span class='ttt' >".showUserName($t->help_uid)."</span>",false)?>
		<?=Inp('check_uid','|檢核人',"<span class='ttt' >".showUserName($t->check_uid)."</span>",false)?>
		<?=Inp('finish','|是否完成',"<span class='ttt' >".($t->finish?'是':'否')."</span>",false)?>
	</div>
	
	<div class="flex" style="border-bottom:none">
		<div class="row" style="flex:3"></div>
		<?=Inp('actions_asd','| ','<span >
			<a href="'.telPage('show').'&id='.$t->id.'">檢視</a> &nbsp;
			<a href="'.telPage('edit').'&id='.$t->id.'">編輯</a> &nbsp;
			<a href="'.telPage('del').'&id='.$t->id.'">刪除</a>
		</span>' ,false)?>
	</div>
</div>
<?
}
}*/
?>