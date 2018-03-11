(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-19140349-9', 'auto');
ga('send', 'pageview');

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
location.get=function (k){
	var data={};
	location.search.substr(1).split('&').forEach(function(s){
		s=s.split('=');
		var k=s.shift(),v=s.join('=');
		v=decodeURIComponent(v);
		
		data[k]=v;
	});
	
	if(k) return data[k];
	return data;
};

location.change=function (data){
	var get=this.get();
	for(var k in data){
		get[k]=data[k];
	}
	
	var arr=[];
	for(var j in get){
		arr.push(
			encodeURIComponent(j)+'='+encodeURIComponent(get[j])
		);
	}
	
	this.href=this.pathname+'?'+arr.join('&');
};
/*

*/

function pT(p){
	if(p.indexOf('T')==-1){
		return p.replace(/\//g,'-').replace(' ','T');
	}else{
		return p.replace('T',' ');
	}
}

function pTs(data,key){
	key.split('|').forEach(function(k){
		data[k]=pT(data[k]);
	});
}

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
		var e=$('input,select,button,textarea').filter('#'+id+',[name="'+id+'"],[name="'+id+'[]"]');
		var v=e.val();
		
		if(e.attr('type')=='checkbox' || e.attr('type')=='radio'){
			if(typeof e.attr('value')!='undefined'){
				v=[];
				e.each(function(){
					if(this.checked) v.push(this.value);
				});
				v=v.join('|');
			}else if(!e.prop('checked')){
				v=0;
			}else{
				v=1;
			}
		}
		if(e.attr('datetime-local')){
			v=v.replace('T',' ');
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

	if(document.documentElement.offsetWidth<=800){
		alert(text);
		cb();
		return;
	}
	var mg=$("#msgbox");
	if(!mg.data('closeEvent')){
		mg.bind('close',msgboxClose);
		mg.data('closeEvent',true);
	}
	
	$('input,select,button').blur();
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
	},3000);
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
		var sel=$('input,select,button,textarea').filter('#'+i+',[name="'+i+'"],[name="'+i+'[]"]');;
		var type=sel.attr('type');
		var v=data[i];
		if(type=='file') continue;

		if(type=='checkbox' || type=='radio'){
			if(sel.attr('value')===undefined){
				v=v=='0'?false:true;
				sel.prop('checked',v);	
			}else{
				v.split('|').forEach(function(o){
					sel.filter('[value="'+o+'"]').prop('checked',true);
				});
			}
			
		}else{
			sel.val(v);
		}
		sel.change();
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
		1:	'主管',
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
		var v=func(i).split('-');
		if(v[1]){
			 v=v[1].trim();
		}else{
			v=v[0];
		}
		target.append(
			'<option value="'+i+'">'+v+'</option>'
		);
	}
	var def=target.attr('def');
	if(def){
		target.val(def);
		target.removeAttr('def');
	}
}

function uploadFile(file,data,cb,upload){
	var maxSize=.5*1024*1024;	//0.5MB;
	var start=0,fid=-1;
	var XHR={
		XHR:null,
		stop:false,
		abort:function(){
			if(XHR.XHR) XHR.XHR.abort();
			XHR.stop=true;
		}
	};
	if(typeof upload!='function') upload=function(){};
	
	data.filename=file.name;
	data.filesize=file.size;
	
	if(data.filesize>2147483648){
		msgbox('檔案大小超過2GB!','error');
		return;
	}
	
	Ajax('File','addFile',data,function(res){
		fid=res.id;
		send();
	});
	
	function fail(text){
		if(text=="abort"){
			cb(-1);
			return;
		}
		send();
	}
	
	function send(){
		if(XHR.stop) cb(-1);
		if(start>=data.filesize){
			cb(fid*1);
			return;
		}
		var sData=new FormData();
		sData.append('start',start);
		sData.append('fid',fid);
		sData.append('data',file.slice(start,start+maxSize));
		XHR.XHR=Ajax('File','storageFile',sData,function(d){
			if(d){
				start+=maxSize;
				var p=start/data.filesize;
				if(p>1) p=1;
				console.log("Upload File:",data.filename,Math.round(p*1000)/10,"%");
				upload(data,p);
				send();
			}else{
				fail();
			}
		},function(jqXHR,text){
			fail(text);
		},{
			processData:	false,
			contentType:	false
		});
	}
	
	return XHR;
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

function TagInput(selector,datalist){
	if(!datalist) datalist=[];

	var randId='';
	while(randId.length<10)
		randId+=Math.floor(Math.random()*26)+65;
	randId='jQuery_'+randId;

	var b=$(selector);
	var tagPlace=$('<div class="tagPlace txt"></div>');
	var input=$('<input type="text" class="inputbox" list="'+randId+'"/>');
	var suggest=$('<datalist id="'+randId+'"></datalist>');
	b.addClass('tagInput');
	datalist.forEach(function(t){
		suggest.append('<option value="'+t+'"/>');
	});
	
	/*Functions Define*/
	var getAllTags=function(){
		var tags=[];
		tagPlace.find('>.tag>.textbox').each(function(t){
			tags.push($(this).text().trim());
		});
		return tags;
	};
	var addTag=function(tag){
		var tags=getAllTags();
		/*The Tag not exist*/
		if(tags.indexOf(tag)==-1){
			var e=$('<div class="tag"><span class="textbox"></span><a class="removeTerm"></a></div>');
			e.find('.textbox').text(tag);
			e.insertBefore(input);
			e.find('.removeTerm').click(function(){
				removeTag(tag);
			});
		}
		input.val('');
	};
	var removeTag=function(value){
		if(typeof value=="number"){
			tagPlace.find('>.tag:nth-child('+(value+1)+')').remove();
		}else{
			var tags;
			while(!tags || tags.indexOf(value)!=-1){
				tags=getAllTags();
				removeTag(tags.indexOf(value));
			}
		}
	};
	/*Functions Define*/
	
	/*Bind Event*/
	input.bind('enterTerm',function(){
		var v=this.value.trim();
		if(!v) return;
		
		addTag(v);
		
	}).bind('keydown',function(e){
		var b=$(this);
		switch(e.keyCode){
			case 8:
				if(this.value.length!=0) return;
				var tags=getAllTags();
				removeTag(tags.length-1);
			break;
			case 13:
				b.trigger('enterTerm');
			break;
		}
	});
	tagPlace.bind('click',function(){
		input.focus();
	});
	/*Bind Event*/
	
	/*Bind Elements*/
	tagPlace.append(input);
	tagPlace.append(suggest);
	b.append(tagPlace);
	
	
	return {
		value:	function(){
			return getAllTags();
		}
	};
}

function ProjectId(n){
	return n.padLeft(4,'0');
}

function TaskId(project_id,project_no){
	return ProjectId(project_id)+'-'+project_no.padLeft(5,'0');
}

function initBindEvent(){
	
	var mch=$('#menu .child');
	mch.bind('mouseup',function(e){
		var t=$(this);
		/*
		mch.each(function(){
			if(this==t[0]) return;
			$(this).removeClass('show');
		});
		*/
		t.toggleClass('show');
		e.stopPropagation();
	});
	$('html').mouseup(function(e){
		$('#menu .child.show').removeClass('show');
	});
	
	$('.mobmenu').click(function(){
		
		var m=$('#menu');
		m.toggleClass('show');
		$(this).toggleClass('more');
		$("#afgs").show();
		if(m[0].classList.contains('show')){
			TITLE($("#afgs").hide().html());
		}else{
			TITLE('');
		}
	});
	
	$('form.form').each(function(){
		var t=$(this);
		var func=window.fcb;

		if(t.attr('sc')){
			func=window[t.attr('sc')];
		}
		var cbt;
		t.find('button,input').click(function(){
			cbt=this;
			setTimeout(function(){
				cbt=null;
			},10);
		});
		if(func) t.submit(function(e){
			$(cbt).addClass('wait');
			func();
			e.preventDefault();
		});
	});
	
	$('input.date').datepicker({
		dateFormat:	'yy-mm-dd'
	});
	
	$('input.datetime').datetimepicker({
		dateFormat:	'yy-mm-dd',
		timeFormat:	'HH:mm'
	});
	if(document.documentElement.offsetWidth<=800){
		$('.date.hasDatepicker').datepicker('destroy').addClass('txt').each(function(){
			this.value=this.value.replace(/\//g,'-');
			this.setAttribute('type',"date");
		});
		
		$('.datetime.hasDatepicker').datetimepicker('destroy').addClass('txt').each(function(){
			this.value=this.value.replace(/\//g,'-').replace(' ','T');
			this.setAttribute('type',"datetime-local");
		});
	}
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
	/*
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
	*/
	
	var hh=$("header").height(),lastFixed=false;
	$(window).scroll(function(e){
		var t=window.scrollY;
		var se=$("#menu,#fake_menu");
		if(t>=hh && lastFixed===false){
			se.toggleClass('scroll');
			lastFixed=true;
		}else if(t<hh && lastFixed==true){
			se.toggleClass('scroll');
			lastFixed=false;
		}
	});
	
	$("input[type^='date']").parent().addClass('fffa').append('<div class="hideSec"></div>');
	$(window).resize(function(){
		$("input[type^='date']").each(function(){
			var t=$(this);
			if(t.width()>240){
				t.parent().find('.hideSec').show();
			}else{
				t.parent().find('.hideSec').hide();
			}
		});
	});
	$(window).load(function(){
		$(window).resize();
		
		$('.flex.fix').each(function(i){
			$(this).attr('index',i);
		});
	});
	$('.setNow').click(function(){
		var input=$(this).parents('.row').find('input');
		var d=new Date().toJSON().substr(0,16);
		if(input[0].classList.contains('hasDatepicker')){
			d=d.replace(/T/,' ');
		}
		input.val(d);
	});
}

function TITLE(title){
	$('#titlettt').html(title);
	
	if(title){
		/*$('#logo').hide();*/
	}else{
		/*$('#logo').show();*/
	}
}
/*
$(function(){
	
});
*/

function getCustField(data){
	var r=$('.row[id^="row__"]');
	var cdata={};
	
	r.each(function(){
		var t=$(this);
		var id=t.attr('id').substr(4);
		
		cdata[id]=getVal(id)[id];
		
		data[id]=cdata[id];
	});
	return cdata;
	
}

function getTelCategory(){
	var data=[];
	data.all={};
	var CateKey='Cate_'+new Date().toJSON().split('T')[0];
	
	var txt=undefined;//localStorage[CateKey];
	!txt && $.ajax({
		url:	'ajax.php?act=TelCate&func=getList',
		type:	'POST',
		dataType:	'text',
		async:	false
	}).done(function(d){
		txt=d;
		//localStorage.setItem(CateKey,txt);
	});
	d=JSON.parse(txt);
	
	var mother={};
	var index=0;
	d.forEach(function(b){
		data.all[b.id]=b;
		with(b){
			var i=mother[big_cate];
			if(!i && i!==0){
				i=index++;
				mother[big_cate]=i;
				
			}
			
			data[i]=data[i] || {};
			data[i].cate=big_cate;
			data[i].child=data[i].child || [];
			data[i].child.push(b);
			data[big_cate]=data[i];
		}
	});
	data.getChild=function(mother){
		if(mother===""){
			return [
			{id:-1,value:"不指定"}
			]
		}
		return data[mother].child;
	};
	return data;
}

function getTelCity(){
	var txt;
	var data=[];
	data.all={};
	var CityKey='TelCity_'+new Date().toJSON().split('T')[0];
	txt=undefined;//localStorage[CityKey];
	!txt && $.ajax({
		url:	'ajax.php?act=TelCity&func=getList',
		type:	'POST',
		dataType:	'text',
		async:	false
	}).done(function(d){
		txt=d;
		//localStorage.setItem(CityKey,txt);
	});
	
var d=JSON.parse(txt);
	var mother={};
	var index=0;
	d.forEach(function(b){
		data.all[b.id]=b;
	});
	data.getAllZone=function(){
		var data=[];
		d.forEach(function(b){
			if(data.indexOf(b.city)==-1){
				data.push(b.city);
			}
		});
		return data;
	};
	data.getAreaByZone=function(zone){
		var data=[];
		d.forEach(function(b){
			var p=b.area_no+'-'+b.area;
			if(b.city==zone && data.indexOf(p)==-1){
				data.push(p);
			}
		});
		return data;
	};
	
	data.getSchoolByArea=function(area_no){
		var data=[];
		d.forEach(function(b){
			if(b.area_no==area_no){
				data.push(b);
			}
		});
		return data;
	};
	
	data.getSchoolByZone=function(zone){
		var data=[];
		d.forEach(function(b){
			if(b.city==zone){
				data.push(b);
			}
		});
		return data;
	};
	return data;
}
