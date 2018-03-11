<?
$t=Tel::getInfo(Input::get('id',-1));
if(!$t){
	$t->no=Tel::getNo();
	$t->id=-1;
	$t->incall_time=date('Y-m-d H:i');
	$t->cate_id=1;
	$t->school_id='000000';
}

?>
<script>
<?
if($t->id==-1){
	echo <<<asd
	$('.wh[txt="新增"]').addClass('sel');
asd;
}
?>
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
.delF{
	position: absolute;
	top: .5em;
	right: .5em;
}
</style>
<form class="form">
<div class="record">
	<div class="flex">
		<?=Inp('id','|編號',"<span class='ttt'>".($t->no)."</span>",false)?>
		<div class="row flex">
			<?=Inp('service','|來源管道',"<select class='txt'><option>".
			implode('</option><option>',[
				'電話','E-mail','LINE','其他'
			])
			."</option></select>",false)?>
			
			<?=Inp('task_uid','|客服人員',"<select disabled class='txt' def='".Session::get('id')."'>
				<option value='-1'>無</option>
			</select>",false)?>
		</div>
		
		
		<?=Inp('incall_time','|來電時間',"<input type='datetime-local' value='".date('Y-m-d\\TH:i')."'>",false)?>
	</div>
	<div class="flex">
		<?=Inp('tea_name','|姓名',"<input autofocus type='text'/>", false)?>
		<?=Inp('tea_phone','|來源',"<input type='text' placeholder='請輸入電話、Email或是其他聯絡方式'/>",false)?>
		<?=Inp('school','|學校',"<span class='flex'>
			<select id='zone' class='txt' required>
			</select>&nbsp;
			<br/>
			<select id='school_id' class='txt' required style='display:block;width:100%'></select>
		</span>
		", false)?>
	</div>
	<div class="flex">
		<?=Inp('problem','|問題描述',"<textarea class='txt'></textarea>",true)?>
		<?=Inp('handle','|處理方式',"<textarea class='txt'></textarea>",false)?>
	</div>

	<div class="flex">
		<?=Inp('problem_suggest', '|常見問題', '<div ></div>', false)?>
	</div>

	<div class="flex">
		<?=Inp('cate','|類別','<div >
		<select id="mother" class="txt" required ></select>
		<select id="child" class="txt" name="cate_id"  required></select>
		</div>');?>

		<?=Inp('help_uid','|協助人員',"<select class='txt'>
			<option value='-1'>無</option>
		</select>",false)?>
	</div>

	<div class="flex">
		<?=Inp('upload_area', '|附檔', '<div ></div> (可一次選多檔)<div id="uploaded_files"></div>', false)?>
		<?=Inp('note','|備註',"<textarea class='txt' rows='3'></textarea>",false)?>
	</div>
	<script>
		function addFInp(){
			var x=$('<input type="file" multiple/>');
			x.bind('change', function(){
				if(this.files.length>0) addFInp();
			});
			$("#upload_area").append(x);
		}
		addFInp()
	</script>


	<div class="flex">
		<?=Inp('check_uid','|檢核人',"<select class='txt' def='36'>
			
		</select>",false)?>
		<?=Inp('cost','|處理時間','<select class="txt">
			<option>&lt;5分鐘</option>
			<option>5~15分鐘</option>
			<option>15~30分鐘</option>
			<option>30~60分鐘</option>
			<option>60分鐘以上</option>
		</select>',false);?>
		<?=Inp('finish_x','|處理情形',"<span class='ttt' >
			<span><input type='radio' name='finish' value='1' > 已完成</span>&emsp;
			<span><input type='radio' name='finish' value='0' checked/> 待處理</span>
		</span>",false)?>
	</div>
	
	<div class="flex" style="border-bottom:none">
		<div class="row" id="zxcsfdhpoj"  style="text-align:center; position:relative">
			<button type="submit" class="bt">送出</button>
			<button type="reset">重填</button>

			<button type="button" style="position:absolute;right: 0;" onclick="tmpSave()">暫存並繼續新增</button>
		</div>
	</div>
	<div hidden>
		<?=Inp('id','id','<input type="hidden"/>')?>
		<?=Inp('done','done','<input type="hidden" value="0"/>')?>
	</div>
</div>
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
			
			sc.append('<option value="'+s.id+'" data-search-text="'+s.schoolname+'">'+vv+'</option>');
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
function saveD(callback){
	var data=getVal('id|tea_name|tea_phone|school_id|problem|handle|note|cate_id|incall_time|finish|help_uid|check_uid|task_uid|cost|done|service');
	data.incall_time=data.incall_time.replace('T',' ');

	var fd=new FormData();
	for(var k in data){
		fd.append(k, data[k]);
	}

	Ajax('Tel','edit',data,function(d){
		if(d) {
			var flist = [];
			$("#upload_area input").each(function () {
				if (this.files.length > 0) {
					for (var i = 0; i < this.files.length; i++) {
						flist.push(this.files[i]);
					}
				}
			});

			var cc = 0;
			flist.forEach(function (f) {
				uploadFile(f, {
					project_id: -2,
					task_id: d===true?<?=$t->id?>:d
				}, function () {
					cc++;
					chkDone();
				})
			});
			$('#zxcsfdhpoj').find('button, input').prop('disabled' ,!0);

			function chkDone(){
				msgbox('正在上傳檔案');
				if(cc!=flist.length) return;
				callback(d);
			}
			chkDone();
		}
	}, null, {
		processData: false,
		contentType: false,
		data:	fd
	});


}
function fcb(){
	saveD(function(d){
		location.href=<?=json_encode(telPage('list'))?>;
		msgbox('操作完成', 'success', function () {
		});
	});
}

function tmpSave(){
	saveD(function(d){
		location.href=<?=json_encode(telPage('edit'))?>;
		d && msgbox('操作完成','success',function(){
		});
	});
}



//$('#row_note').addClass('spp');

$('#school_id').combobox();
</script>
<style>
#school #zone{width:110px}
#school .custom-combobox{flex:1}
.sug{
	color: #4285F4;
	padding: 0 .5em;
	margin: 0 .25em;
	border-bottom: 2px dashed #4285F4;
	font-size: smaller;
	cursor: pointer;
	background: #F5F5F5;
}
</style type="text/javascript">
<script>
(function(list){
	var div=$("#problem_suggest").empty();
	list.forEach(function(d){
		var s=$('<span></span>');
		s.addClass('sug');
		s.text(d.keyword);
		s.attr('title', [d.problem, d.handle].join('\n\n'));
		s.click(function(){
			$('#problem').val(d.problem);
			$('#handle').val(d.handle);
			Ajax('TelSuggest', 'addClick', {
				id:	d.id
			}, function(){});
		});
		div.append(s);
	});
})(<?=json_encode(TelSuggest::getList([]))?>);

function refFileList(){
	Ajax('File','getFiles',{
		tid:	<?=$t->id?>,
		pid:	-2
	},function(flist){
		var list=$("#uploaded_files").empty();
		flist.forEach(function(f){
			with(f){
				list.append('\
						<div class="uploadF" href="<?=mkLink('File','getFile')?>&id='+id+'">\
							<div class="fn">'+filename+'</div>\
							<div class="row">\
								<!--<div class="ud">'+upload_time+'</div>-->\
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
