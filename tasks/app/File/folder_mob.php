<style>
.files .w {
    display: flex;
    flex-flow: row;
	width:100%;
}

.files .filetype {
    /*width: 18px;*/
	margin-top: 5px;
}

.files .info {
    flex: 1;
    margin-left: 16px;
	background-image:none;
}
#addFileSelect{
	display:none;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	opacity: 0;
	z-index:99;
}
#filesList{
	margin-top:2em;
}
.ltitle{
	flex:1;
}
.desc{
	display:none;
}
</style>
<stylef class="hide">
<?
if(!isAdmin()){
	echo '.del{display:none}';
}
$pid=getVal($_GET,'pid',-1);
?>
#filesList{
	-webkit-user-select:none;
	-moz-user-select:none;
	-ms-user-select:none;
	user-select:none;
}
.permission_row>td{
	border-top:1px solid #CCC;
}
.permission_row td:not(:first-child){
	width:1px;
}
.permission_row.everyone .del{visibility:hidden}
.table{
	font-size:14px;
	font-family:'新細名體';
}
.table td{
	padding:0;
}
.cell{
	padding:5px 5px;
	overflow:hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	position:relative;
}

.name.cell{
	overflow:visible;
}
.time{width: 120px;}
.size{width: 60px;text-align:right;}
.type{
	width:70px;
}
.name .action{
/*
	opacity:0;
	transition:opacity .2s;
*/
	display:none;
	position: absolute;
	background:#FFF;
	border: 1px solid #888;
	border-radius: 3px;
	overflow:hidden;
	padding: 0;
	box-shadow: #333 1px 1px 5px;
	z-index:3;
}
.name .action.show{
	display:block;
	opacity:1;
}
.action .icon{
	display:block;
	padding:8px 4px;
}
.action .icon:hover{
	background:#D3E4F3;
}
.name .fn{
	cursor:pointer;
}
.name .fn:hover{
	text-decoration:underline;
	color:blue;
}
tr[data-type="dir"] .action .rev{
	display:none;
}
.noaction .action{display:none}
.comment{
	width:200px;
}
</stylef>
<div class="hide">

<div class="row">
	<label for="currentPath" style="display:inline;width:70px;">目前位置:</label>
	<input id="currentPath" type="text" style="width: calc(100% - 80px);display:inline-block;background:#FFF;" disabled/>
</div>

<div class="row">
	<div id="searchFile" class="left" style="width:80%">
		<input type="search" placeholder="在此資料夾搜尋檔案" class="txt" id="kw"/>
	</div>
	<div id="func" class="right">
		<button id="addFolder">新增資料夾</button>
		<button class="bt" id="addFile">
			上傳檔案
			<!--<input type="file" multiple="multiple" id="upload" style="position:absolute;left:0;right:0;top:0;bottom:0;opacity:0;"/>-->
		</button>
	</div>
	
	<script>
	$("#addFolder").click(function(){
		$('\
		<div>\
			<label for="name">資料夾名稱:</label>\
			<input type="text" id="name" placeholder="新增資料夾"/>\
			<input type="hidden" id="fid" value="'+fid+'"/>\
			<input type="hidden" id="pid" value="<?=$pid?>"/>\
		</div>\
		').dialog({
			modal:	true,
			close:	function(){
				$(this).remove();
			},
			buttons:	{
				'確定':	function(){
					var t=this;
					var data=getVal('name|fid|pid');
					if(data.name.trim()==''){
						msgbox('請輸入資料夾名稱','error');
						return;
					}
					Ajax('Folder','addFolder',data,function(d){
						if(typeof d=='number'){
							if(d!=-1){
								msgbox('新增完成','success');
							}
							$(t).dialog('close');
							ref();
						}else{
							msgbox(d,'error');
						}
					});
				},
				'取消':	function(){
					$(this).dialog('close');
				}
			},
			title:	'新增資料夾',
			width:	250,
			resizable:	false,
		});
	});
	
	$("#addFile").click(function(){
		var upload_progress,XHR;
		$('\
		<div>\
			\
			<div class="row">\
				<label for="showname">標題</label>\
				<input type="text" id="showname" placeholder="請輸入標題"/>\
			</div>\
			<div class="row required">\
				<label for="upload">請選擇檔案:</label>\
				<input type="file" id="upload"/>\
			</div>\
			\
			<div class="row">\
				<label for="comment">備註</label>\
				<input type="text" id="comment" placeholder="請輸入備註"/>\
			</div>\
			<div class="row">\
				<label for="progress">上傳進度</label>\
				<div id="upload_progress"><div class="value">0%</div></div>\
			</div>\
			<div class="row r">\
				<i>單檔最大2GB</i>\
			</div>\
			\
			<input type="hidden" id="fid" value="'+fid+'"/>\
			<input type="hidden" id="pid" value="<?=$pid?>"/>\
		</div>\
		').dialog({
			modal:	true,
			close:	function(){
				if(XHR){
					XHR.abort();
				}
				$(this).remove();
			},
			buttons:	{
				'確定':	function(){
					var t=$(this);
					var data=getVal('comment|fid|pid|showname');
					data.files=$('#upload')[0].files;
					if(data.files.length==0){
						msgbox('請選擇檔案','error');
						return;
					}
					XHR=uploadFile(data.files[0],{
						project_id:	data.pid,
						folder_id:	data.fid,
						comment:	data.comment
					},function(d){
						if(typeof d=="number"){
							refView();
							t.dialog('close');
							if(d!=-1) msgbox('上傳完成','success');
						}else{
							msgbox('上傳錯誤','error');
							upload_progress.progressbar({value:0});
						}
					},function(data,progress){
						upload_progress.progressbar({value:progress});
					});
					
				},
				'取消':	function(){
					$(this).dialog('close');
				}
			},
			title:	'上傳檔案',
			width:	500,
			resizable:	false,
		});
		upload_progress=
		$('#upload_progress').progressbar({
			max:1,
			value:0,
			change:function(){
				var p=Math.round($(this).progressbar('value')*1000)/10;
				p+='%';
				$(this).find('.value').text(p);
				
			}
		});
	});
	$('#kw').bind('input',function(){
		var v=this.value.trim();
		if(v==""){
			$("#func").css('visibility','visible');
		}else{
			$("#func").css('visibility','hidden');
		}
		ref();
	});
	</script>
</div>

</div>

<div id="filesList"></div>

<select id="addFileSelect">
	<option>資料夾</option>
	<option>檔案</option>
</select>
<script>
var FOLDER;
var fid=<?=getVal($_GET,'fid',0)?>;
var currentFolder;
function ref(){
	Ajax('Folder','getFolders',{
		/*fid:	<?=getVal($_GET,'fid',0)?>*/
		pid:	<?=$pid?>
	},function(d){
		FOLDER=d;
		refView();
	});
}

function getChildFolderId(id){
	var arr=[id];
	var base=FOLDER[id];
	
	base.childs.forEach(function(b){
		var newArr=getChildFolderId(b.id,arr);
		newArr.forEach(function(e){
			arr.push(e*1);
		});
	});
	console.log(id,arr);
	return arr;
}

function mkRow(id,filename,size,type,time,permission,comment){
	if(!comment) comment='';
	
	var ext=filename.substr(filename.lastIndexOf('.')+1);
	if(ext==filename) ext="";
	if(type=="dir") ext=".";
	
	var html='\
	<div class="task files">\
		<div class="w">\
			<div class="filetype" ext="'+ext+'"></div>\
			<div class="info flex">\
				<div class="ltitle">'+filename+'</div>\
				<div class="bot fix flex">\
					<div class="name">'+(time.substr(5,11))+'</div>\
					<div class="desc eval">'+(type!='dir'?minByte(size):'')+'</div>\
				</div>\
			</div>\
		</div>\
	</div>\
	';
	
	html=$(html);
	
	html.click(function(){
		clickName(id,type);
	});
	
	return html;
}

function mkRow2(id,filename,size,type,time,permission,comment){
	if(!comment) comment='';
	
	var ext=filename.substr(filename.lastIndexOf('.')+1);
	if(ext==filename) ext="";
	if(type=="dir") ext=".";

	var tr;
	var action='\
	<div class="action">\
		<div class="icon rev" title="歷史版本" permission="2">歷史版本</div>\
		<div class="icon rename" title="重新命名" permission="1">重新命名</div>\
		<div class="icon permission" title="權限管理" permission="7">權限</div>\
		<div class="icon del" title="刪除" permission="3">刪除</div>\
	</div>';
	var arr=[
		'<div class="name cell">\
			'+action+'\
			<div class="fn">\
				<span class="filetype" ext="'+ext+'"></span>\
				<span class="filen">'+filename+'</span>\
			</div>\
		</div>',
		'<div class="type cell">'+type+'</div>',
		'<div class="size cell" value="'+size+'">'+minByte(size)+'</div>',
		'<div class="time cell">'+time+'</div>',
		'<div class="comment cell" title="'+comment+'">'+comment+'</div>'
	];
	if(type=='dir'){
		arr[2]='<div class="size cell" value="-1">-</div>';
	}
	tr=$(mkTr(arr));
	tr.attr('data-id',id);
	tr.attr('data-type',type);
	tr.addClass('fileInfo');
	var fn=tr.find('.name .fn').click(function(){
		clickName(id,type);
	});
	tr.dblclick(function(){fn.click()});
	
	tr.find('.del').click(function(){
		delObj(id,type);
	});
	tr.find('.rev').click(function(){
		location.href='<?=mkLink('File','show')?>&fid='+id;
	});
	tr.find('.rename').click(function(){
		renameObj(id,type,filename);
	});
	tr.find('.permission').click(function(){
		editPermission(id,type=='dir'?'folder':'file');
	});
	
	tr.bind('contextmenu',function(e){
		switch(e.which){
			case 3:
				var action=$(this).find('.action');
				$(document).one('mouseup',function(){action.removeClass('show')});
				action.addClass('show').position({
					my:	"left top",
					at:	'left top',
					of:	e
				});
				e.preventDefault();
				e.stopPropagation();
			break;
		}
	});
	
	
	if((permission & 0x2) == 0) return '';
	
	tr.find('.action>div').each(function(){
		var p=parseInt(this.getAttribute('permission'),10);
		if((permission & p) != p) this.style.display='none';
	});
	
	
	return tr;
}

function refView(){
	currentFolder=FOLDER[fid];
	with(currentFolder){
		if((permission & 1) == 0){
			$("#func").css('visibility','hidden');
		}else{
			$("#func").css('visibility','visible');
		}
	}

	var kw=$("#kw").val().trim().toLowerCase();
	var list=$("#filesList").empty();
	var path=currentFolder.path.join('/');
	
	if(!path) path='/';
	$("#currentPath").val(path);
	var last=path.split('/').pop();
	if(last==""){
		TITLE("檔案分享");
	}else{
		TITLE(last);
	}

	var fileData={
		fid:	fid,
		pid:	<?=$pid?>,
		group:	true
	};
	
	if(kw){
		fileData['ids']=getChildFolderId(fid);
		fileData['kw']=kw;
	}

	if(path!='/' && kw==""){
		list.append(mkRow(
			currentFolder.parent_id,
			'..',
			'-',
			'dir',
			'-',
			3
		).addClass('noaction'));
	}
	
	var folders=[];
	if(!kw){
		folders=currentFolder.childs.clone();
	}else{
		for(var i=1;i<fileData['ids'].length;i++){
			folders.push(
				FOLDER[
					fileData['ids'][i]
				]
			);
		}
	}
	
	for(var i=0;i<folders.length;i++){
		var b=folders[i];
		if(kw!="" && !b.name.searchInArray(kw.split(' '))) continue;

		list.append(mkRow(
			b.id,
			b.name,
			'-',
			'dir',
			b.create_time,
			b.permission
		));
	}
	
	Ajax('File','getFiles',fileData).done(function(d){
		d.forEach(function(b){
			list.append(mkRow(
				b.id,
				b.showname || b.filename,
				b.filesize,
				b.type.split('/')[0],
				b.create_time,
				b.permission,
				b.comment
			));
		});
	});
}

function clickName(id,type){
	$('#kw').val('');
	if(type=='dir'){
		fid=id;
		refView();
	}else{
		location.href='<?=mkLink('File','getFile',[
			'sp'	=>	1
		])?>&id='+id;
	}
}

function renameObj(id,type,filename){
	var ok=function(){
		var t=$(this);
		var data=getVal('fid|name|type');
		data.same=true;
		
		var act='File';
		if(type=='dir'){
			act='Folder';
		}
		
		if(data.name==""){
			msgbox('請輸入名稱','error');
			return;
		}
		
		Ajax(act,'rename',data,function(d){
			if(d===true){
				ref();
				msgbox('操作完成','success');
			}else if(typeof d=="string"){
				msgbox(d,'error');
			}
			t.dialog('close');
		});
	};
	
	$('\
	<div>\
		<div class="row">\
			<label for="name">名稱</label>\
			<input type="text" id="name" placeholder="請輸入名稱"/>\
			<input type="hidden" id="fid" value="'+id+'"/>\
		</div>\
	</div>').dialog({
		modal:	true,
		resizable:	false,
		width:	250,
		title:	'重新命名',
		close:	function(){
			$(this).remove();
		},
		buttons:	{
			'確定':	ok,
			'取消':	function(){
				$(this).dialog('close');
			}
		}
	});
	$('#name').val(filename).select();
}

function delObj(fid,type){
	if(!confirm('您確定要刪除所選的物件?')) return;
	var data={
		fid:	fid,
		same:	true
	};
	var act='File';
	
	if(type=='dir'){
		act='Folder';
	}
	
	Ajax(act,'delete',data,function(d){
		if(d){
			ref();
			msgbox('刪除成功','success');
		}else{
			msgbox('刪除失敗','error');
		}
	});
}

function editPermission(ref_id,type){
	function addUser(uid,permission){
		if(typeof permission=="undefined") permission=<?=json_encode(Permission::$default)?>;

		var selectOption='\
			<select class="permissionOptions">\
				<option value="7">完全存取</option>\
				<option value="3">允許讀寫</option>\
				<option value="2">僅讀取</option>\
				<option value="0">無法存取</option>\
			</select>\
		';
		var userName=USERS[uid];
		if(uid==-1) userName='所有人';
		userName+='&nbsp;';
		var tr=$(mkTr([
			userName,
			selectOption,
			'<div class="icon del" title="移除"></div>'
		]));
		tr.addClass('permission_row');
		
		tr.find('.del').click(function(){
			$(this).parents('.permission_row').remove();
		});
		
		if(uid==-1) tr.addClass('everyone');
		tr.find('.permissionOptions')
		.data('uid',uid).val(permission);
		
		var everyone=list.find('.permission_row.everyone');
		if(everyone.length==0){
			list.append(tr);
		}else{
			tr.insertBefore(everyone);
		}
		
	}

	var box=$('\
	<div style="font-size:12px">\
		<input type="hidden" id="p_uid"/>\
		<button class="bt right" id="selectUser">選擇使用者</button>\
		<table id="userPermissionList" width="100%" cellpadding="3" cellspacing="0"></table>\
	</div>\
	').dialog({
		modal:	true,
		resizable:	false,
		width:	600,
		height:350,
		title:	'權限管理',
		position:{
			of:	'#filesList',
			my:	'top',
			at:	'top'
		},
		close:	function(){
			$(this).remove();
		},
		buttons:{
			'確定':function(){
				var d={
					ref_id:	ref_id,
					type:	type,
					data:	[]
				};
				$(".permissionOptions").each(function(){
					var uid=$(this).data('uid');
					
					d.data.push({
						ref_id:	ref_id,
						type:	type,
						uid:	uid,
						permission:	this.value
					});
				});

				Ajax('Permission','setPermission',d,function(res){
					if(res){
						msgbox('修改完成');
						ref();
						box.dialog('close');
					}
				});
				
			},
			'取消':	function(){
				$(this).dialog('close');
			}
		}
	});
	var list=$("#userPermissionList");
	
	$('#selectUser').click(function(){
		var v=[];
		$('.permissionOptions').each(function(){
			var t=$(this);
			var uid=t.data('uid')
			v.push(uid);
		});

		window.open(
			'<?=mkLink('User','select',[
				'target'	=>	'p_uid',
				'ispop'		=>	1,
				'type'		=>	'radio'
			])?>#'+v.join(','),
			'puid',
			'width=640,height=480'
		).focus();
	});
	
	$("#p_uid").change(function(){
		var v=[];
		$('.permissionOptions').each(function(){
			var t=$(this);
			var uid=t.data('uid')
			v.push(uid);
		});
		
		var uid=this.value;
		if(v.indexOf(uid)!=-1) return;
		addUser(uid);
	});
	
	Ajax('Permission','getPermission',{
		ref_id:	ref_id,
		type:	type
	},function(d){
		list.empty();
		
		d.forEach(function(b){
			addUser(b.uid,b.permission);
		});
	});
}
/*
addIcon('mobile/add.png',function(){
	$('#addFileSelect').val('');
});
(function(){
	function c(){
		var b=$('.iicon[img="mobile/add.png"]');
		if(b.length==0){
			setTimeout(c,1);
			return;
		}
		
		var s=$('#addFileSelect');
		$('.iicon[img="mobile/add.png"]').append(s);
	}
	c();
})();
*/
ref();
</script>