String.prototype.padLeft=Number.prototype.padLeft=function(n,c){
	if(!c) c=' ';
	var s=this.toString();
	while(s.length<n){
		s=c+s;
	}
	return s;
};
String.prototype.searchInArray=function(arr){
	var find=arr.join('').trim().length>0;
	var me=this.toString().toLowerCase();
	var i=arr.length;
	
	while(find && i--){
		var search=arr[i].toLowerCase();
		if(me.indexOf(search)==-1) find=false;
	}
	return find;
};
Array.prototype.clone=function(){
	var n=[];
	for(var i=0;i<this.length;i++){
		n[i]=this[i];
	}
	return n;
};

function Ajax(act,func,data,cb,failcb,othOp){
	var options={
		url:	'ajax.php?act='+act+'&func='+func,
		data:	data,
		dataType:'json',
		type:	'POST'
	};
	if(typeof othOp=="object"){
		for(var i in othOp) options[i]=othOp[i];
	}

	return $.ajax(options).done(cb).fail(failcb);
}

function getVal(ids){
	var Data={};
	ids.split('|').forEach(function(id){
		var e=$('#'+id+',[name="'+id+'"]');
		var v=e.val();
		
		if(e.attr('type')=='checkbox'){
			if(typeof e.attr('value')!='undefined'){
				if(e.prop('checked')){
					v=e.attr('value');
				}else{
					return;
				}
			}else if(!e.prop('checked')){
				v=0;
			}else{
				v=1;
			}
		}
		Data[id]=v;
	});
	
	return Data;
}

function mkTr(arr,th){
	if(!th){
		return '<tr><td>'+arr.join('</td><td>')+'</td></tr>';
	}else{
		return '<tr><th>'+arr.join('</th><th>')+'</th></tr>';
	}
}

function msgboxClose(e,force){
	var mg=$(this);
		
	mg.removeClass('show');
	clearTimeout(mg.data('timer'));
	
	if(force!==true){
		var cb=mg.data('afterClose');
		if(typeof cb!="undefined") cb();
	}
	
}

function msgbox(text,type,cb){
	var mg=$("#msgbox");
	if(!mg.data('closeEvent')){
		mg.bind('close',msgboxClose);
		mg.data('closeEvent',true);
	}
	
	mg.focus();
	mg.data('afterClose',cb);
	mg.trigger('close',true);
	mg.attr('type',type);
	
	var textArea=mg.find('>.w');
	textArea.empty().append(text);
	textArea.one('click',function(){
		mg.trigger('close');
	});
	$(document).one('keydown',function(){
		mg.trigger('close');
	});
	mg.addClass('show');
	
	var timer=setTimeout(function(){
		mg.trigger('close');
	},2000);
	mg.data('timer',timer);
}

function setTableWidth(tbody){
	var table=$(tbody).parent();
	var ths=table.find('th').get();
	var tr=table.find('tbody>tr');
	var width={};
	for(var i=0;i<ths.length;i++){
		width[i]=$(ths[i]).width();
	}
	
	tr.each(function(){
		var tds=Array.prototype.slice.call(this.children);
		for(var i=0;i<tds.length;i++){
			$(tds[i]).width(width[i]);
		}
	});
	
}

function passVal(data){
	for(var i in data){
		var sel=$('#'+i+',[name="'+i+'"]');
		var type=sel.attr('type');
		var v=data[i];
		if(type=='file') continue;

		if(type=='checkbox'){
			v=v=='0'?false:true;
			sel.prop('checked',v);
		}else{
			sel.val(v);
		}
	}
}

function getPriority(p){
	var txt={
		1:	'緊急',
		2:	'優先',
		3:	'一般'
	};
	
	if(typeof p == 'undefined') return txt;
	
	var t=txt[p];
	p=p.padLeft(2,'0');
	return p+' - '+t;
}

function getEval(p){
	var txt={
		1:	'嚴重落後',
		2:	'落後',
		3:	'正常',
		4:	'領先',
		99:	'暫停'
	};
	
	if(typeof p == 'undefined') return txt;
	
	var t=txt[p];
	p=p.padLeft(2,'0');
	return p+' - '+t;
}

function getAdmin(p){
	var txt={
		0:	'一般使用者',
		1:	'主持人',
		2:	'管理員',
		4:	'專案經理',
		9:	'超級管理員'
	};

	if(typeof p=="undefined") return txt;
	return txt[p];
}

function appendGetToSelect(target,func){
	var txt=func();
	target=$(target);
	for(var i in txt){
		target.append(
			'<option value="'+i+'">'+func(i)+'</option>'
		);
	}
	var def=target.attr('def');
	if(def){
		target.val(def);
		target.removeAttr('def');
	}
}

function uploadFile(file,data,cb){
	var maxSize=8*1024*1024;	//1MB;
	var start=0,fid=-1;
	
	data.filename=file.name;
	data.filesize=file.size;
	
	Ajax('File','addFile',data,function(res){
		fid=res.id;
		send(res);
	});
	
	function fail(){
		debugger;
		send(res);
	}
	
	function send(res){
		if(start>=data.filesize){
			cb(fid*1);
			return;
		}
		var sData=new FormData();
		sData.append('start',start);
		sData.append('fid',fid);
		sData.append('data',file.slice(start,start+maxSize));
		Ajax('File','storageFile',sData,function(d){
			if(d){
				start+=maxSize;
				var p=start/data.filesize;
				if(p>1) p=1;
				console.log("Upload File:",data.filename,Math.round(p*1000)/10,"%");
				send(res);
			}else{
				fail();
			}
		},function(){
			fail();
		},{
			processData:	false,
			contentType:	false
		});
	}
}

function minByte(size){
	var num=[0,10,20,30,40];
	var str=['','K','M','G','T'];
	
	var n=size;
	var i=num.length;
	while(i--){
		var d=Math.pow(2,num[i]);
		if(size>=d){
			n/=d;
			break;
		}
	}
	return (Math.round(n*100)/100)+str[i]+'B';
}

function sortTableWithIndex(table,index,type){
	var tbody=table.find('>tbody');
	var tdata=table.find('>tbody tr').clone(true).get();
	index=index*1;
	var comp=function(a,b){
		var x=cmpGetVal(a.children[index]);
		var y=cmpGetVal(b.children[index]);
		var s=1,d=0;
		if(type!='ASC') s=-1;
		
		if(typeof x!==typeof y){
			x=x.toString();
			y=y.toString();
		}
		console.log(x,y);
		
		if(x<y) d=-1;
		if(x>y) d=1;
		return d*s;
	};
	
	tbody.empty();
	tdata.sort(comp);
	for(var i=0;i<tdata.length;i++){
		tbody.append(tdata[i]);
	}
}

function cmpGetVal(v){
	var base=$(v);
	var v=base.text().trim();
	if(base.attr('value')!==undefined){
		v=base.attr('value');
	}else{
		var firstV=base.find('>*[value]:first');
		if(firstV.length>0){
			v=firstV.attr('value');
		}
	}
	
	if(isFinite(v) && v!=='') return parseFloat(v);
	
	return v;
}

function extractMenu(menu,called){
	var html='';
	html+='<ul class="folder" status="'+(called?'hide':'show')+'">';
	if(!called && menu.name){
		html+='<li class="base" fid="'+menu.id+'">'+menu.name+'</li>'
	}
	menu.childs.forEach(function(m){
		html+='\
			<li>\
				<div class="folderName" fid="'+m.id+'" create_time="'+m.create_time+'" owner="'+m.owner+'">'+m.name+'</div>\
				'+extractMenu(m,true)+'\
			</li>\
		';
	});
	html+='</ul>';
	
	return html;
}

function initBindEvent(){
	if(window.fcb){
		$('form.form').submit(function(e){
			window.fcb();
			e.preventDefault();
		});
	}
	
	$('input.date').datepicker({
		dateFormat:	'yy-mm-dd'
	});
	
	$('input.datetime').datetimepicker({
		dateFormat:	'yy-mm-dd',
		timeFormat:	'HH:mm:ss'
	});
	
	$('select[def]').each(function(){
		this.value=this.getAttribute('def');
	});
	
	$('a>button').click(function(e){
		var a=$(this.parentNode);
		var target=a.attr('target');
		var href=a.attr('href');
		
		if(target){
			window.open(href,target);
		}else{
			location.href=href;
		}
		
		e.preventDefault();
	});
	
	$('.table th').each(function(i){
		this.setAttribute('index',i);
	}).click(function(){
		var t=$(this);
		var type='DESC';
		if(t.attr('type')=='DESC') type='ASC';

		if(t.attr('sortable')=='false' || t.html().trim()==='') return;
		t.parent().find('th').removeAttr('type');
		t.attr('type',type);
		sortTableWithIndex($(this).parents('.table'),t.attr('index')*1,type);
	});
}

/*
$(function(){
	
});
*/