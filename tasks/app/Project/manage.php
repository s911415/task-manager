<br/>
<style>
#cat_span select{
	display:inline-block;
	width:auto;
	margin-right:.5rem;
}
<?
$allow=[
9,4
];
if(in_array(Session::get('admin')*1,$allow,true)){
?>
.edit{
	display:inline-block !important;
}
<?
}?>
</style>

<h2 class="title" id="fdiho">專案管理</h2>
<div class="clear"></div>
<script>
$(function(){
	setTimeout(function(){
		var t=$("#fdiho");
		TITLE(t.text().trim());
		t.hide();
	},200);
	
});
</script>


<div id="filter" class="row">
	<div class="wh " id="nn1" n="2">
		專案分類
	</div>
	<div class="wh " id="nn2" n="1">
		專案列表
	</div>
</div>
<script>
$(".wh").click(function(){
	var t=$(this);
	var n=t.attr('n')*1;
	$("#tab_container").attr('tab',n);
	$('.wh').removeClass('sel');
	t.addClass('sel');
	
	switch(n){
		case 1:
			ref();
		break;
		case 2:
			var ls=$('.currentt');
			if(ls.length==0){
				refViaTree({cat:"***"});
			}else{
				ls.find('>span').click();
			}
			
		break;
	}
});
</script>


<style>
.wh{
	height: 3rem;
	line-height: 3rem;
	width: 10rem;
	background-color: #09F !important;
}
#tab_container[tab="1"] #ogihijghoi{
	visibility:visible;
}
#tab_container[tab="1"] #menusdigh{
	display:none;
}
#tab_container[tab="2"] #ogihijghoi{
	visibility:hidden;
}
#tab_container[tab="2"] #odifheru3,#tab_container[tab="2"] #q09fgu0{
	display:none;
}
#menusdigh{
	width: 240px;
	margin-right: 10px;
	padding-right: 10px;
	border-right: #CCC 1px solid;
}
</style>
<section id="tab_container" tab="2">

<div class="flex" id="q09fgu0">
	<div style="flex:1" id="ogihijghoi">
		<?=Inp('name','','<input type="search" class="txt" placeholder="搜尋專案" oninput="ref()" onchange="ref()"/>',false)?>
		
		<?=Inp('cat_span','|專案分類','<span > </span>',false)?>
	</div>
	<div style="width:120px;text-align:right" id="noud09fh">
		<?
		$allowAdmin=[1,9];
		if(in_array(getVal($_SESSION,'admin',0),$allowAdmin)){
		?>
			<a href="<?=mkLink('Project','edit',['id'=>-1])?>">
				<button class="bt">新增專案</button>
			</a>
		<?}?>
	</div>
</div>
<div class="flex" id="odifheru3">
	<span style="width:250px">
	</span>
	<span class="hr">&nbsp;</span>
</div>
<script>
Ajax('Project','getAllCategory',null,function(d){
	makeTree(d);
	var childs=d.child;
	var span=$('#cat_span');
	var maxDeep=d.maxDeep;
	function makeSelect(child,deep){
		var se=span.find('select');
		for(var i=deep;i<maxDeep;i++){
			se.eq(i).remove();
		}
		
		var select=$('<select></select>');
		select.addClass('txt').attr('name','cat[]');
		select.append('<option value="">[全部]</option>');
		//debugger;
		for(var c in child){
			var oc=child[c];
			if(!oc.child) continue;
			
			var op=$('<option></option>');
			op.text(c).val(c).data('child',oc.child);
			
			select.append(op);
		}
		if(select.find('option').length>1 || deep==0){
			span.append(select);
		}
		select.change(function(){
			var sop=$(this.selectedOptions);
			var child=sop.data('child');
			makeSelect(sop.data('child'),deep+1);
			ref();
		});
	}
	
	makeSelect(childs,0);
});
/*
Ajax('Project','getAllCategory',null,function(d){
	d.forEach(function(b){
		var v=b;
		if(!b){
			 b="[未分類]";
		}
		$("#cat_span").append('<label class="catt"><input type="checkbox" name="cat" value="'+b+'"/>'+b+'</label>');
	});
	
	var cat=$('[name="cat"]');
	cat.change(function(){
		if(cat.filter(':checked:not(#selAll)').length==d.length){
			$('#selAll').prop('checked',true);
		}else{
			$('#selAll').prop('checked',false);
		}
		ref();
	});
});
*/
$('#selAll').change(function(){
	 var cat=$('[name="cat"]');
	if(this.checked){
		cat.prop('checked',true);
	}else{
		cat.prop('checked',false);
	}
	ref();
});

</script>
<style>
.catt{
	display:inline-block !important;
	margin-left:.25rem;
}
</style>
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
</style>
<div class="flex">
	<div id="menusdigh">
		<h3 style="margin-top:0;margin-bottom:.5rem">專案分類</h3>
		
		<div id="tree">
			
		</div>
	</div>
	<div style="flex:1">
		<div id="headdd">
			<div class="task">
				<div class="row project_name">專案經理</div>
				<div class="row title">專案名稱</div>
				<div class="row project_name">
					建立者
				</div>
				<div class="row action">功能</div>
				<div class="row deadline_time">建立日期</div>
			</div>
		</div>

		<div id="list"></div>
	</div>
</div>


<!--
<table class="table" id="plist" style="display:none">
	<thead>
		<tr>
			<th>#</th>
			<th>分類</th>
			<th>名稱</th>
			<th>提出者</th>
			<th>PM</th>
			<th>結案</th>
			<th width="250"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
-->
<style>
.cat{
  display: inline-block;
  font-size: .75rem;
  background: #DDD;
  color: #666;
  margin-right: .25rem;
  padding: .2rem .25rem;
}

.row.project_name{
	width:6rem;
}
</style>
<script>
function mkCat(cat){
	var cat=cat.trim().split(',');
	if(cat.length==1 && cat[0]=="") return '';
	var aa=[];
	cat.forEach(function(c){
		aa.push('<div class="cat">'+c+'</div>');
	});
	return aa.join('');
}
function ref(){
	$("#plist").show();

	var data=getVal('name');
	var cat=[];
	$('#cat_span>select').each(function(){
		var c=this.value;
		if(c){
			cat.push(c);
		}
	});
	
	if(cat.length>0){
		data.cat=cat.join('|');
	}
	
	data.tab=$("#tab_container").attr('tab');
	Ajax('Project','getManageList',data,appendList);
}

function appendList(d){
	var list=$("#list").empty();
	var buf=[];
	d.forEach(function(b){
		with(b){
			var dd=create_time.replace(/-/g,' ').split(' ');
			html=$(
				'<div class="task" data-pid="'+id+'">\
					<div class="row project_name">'+USERS[pm]+'</div>\
					<div class="row title"><span class="cate">'+mkCat(category)+'</span><span style="-webkit-flex:1;overflow:hidden;text-overflow:ellipsis">'+name+'</span></div>\
					\
					<div class="row project_name">'+USERS[owner]+'</div>\
					\
					<div class="row action">\
						<div class="act_icon edit" title="編輯" allow="1"></div>\
					</div>\
					<div class="row deadline_time">'+dd[1]+'/'+dd[2]+'</div>\
					\
				</div>'
			);
		}
		list.append(html);
	});
	
	bindEvent();
	/*
	setTableWidth(list);
	$("#list").sortable({
		placeholder:	'highlight',
		update:	function(){
			var tr=list.find('>tr');
			var data={};
			tr.each(function(i){
				var id=$(this).find('.id').text();
				id=parseInt(id,10);
				data[id]=i;
			});
			Ajax('Project','setOrder',data,function(){});
		}
	});
	*/
}

function bindEvent(){
	$('.edit').click(function(e){
		var t=$(this).parents('.task');
		var pid=t.data('pid');
		location.href='<?=mkLink('Project','edit')?>&id='+pid;
		
		e.stopPropagation();
	});
	$('.task').click(function(){
		var t=$(this);
		var tid=t.data('tid');
		t.find('.act_icon:visible:last').click();
		//location.href="<?=mkLink('Task','show')?>&tid="+tid+'&back='+encodeURIComponent(location.href);
	});
	
	$('.manageProject').click(function(){
		var pid=this.getAttribute('pid');
		location.href='<?=mkLink('Task','manage')?>&pid='+pid;
	});
	$('.delProject').click(function(){
		var pid=this.getAttribute('pid');
		if(
			<?=getVal($_SESSION,'admin',0)?>>=1 &&
			confirm('Are you sure?')
		){
			Ajax('Project','delProject',{
				pid:	pid
			},function(d){
				if(d){
					ref();
					msgbox('刪除成功','success');
				}else{
					msgbox('刪除失敗','error');
				}
			})
		}
	});
	
}
//ref();

function makeTree(d){
	function gMenu(m,path){
		path=path || [];
		var html='';
		html+='<ul>';
		with(m){
			path.push(value);
			var len=0;
			for(var c in child){len++}if(typeof child.length=="number"){for(var c in []){len--}}

			html+='<li cat="'+value+'" class="liName" path="'+path.join(',')+'" childCount="'+len+'" treeStatus="'+(len==0?'none':'collapse')+'"><span cat="'+value+'">'+value+'</span>';
			if(typeof child.length!="number"){
				for(var c in child){
					html+=gMenu(child[c],path);
				}
			}
			html+='</li>';
			path.pop();
			
		}
		html+='</ul>';
		
		return html;
	}
	
	var html='';
	var t=$("#tree").empty();
	for(var c in d.child){
		html+=gMenu(d.child[c]);
	}
	t.append(html);
	
	$('.liName span').click(function(){
		var li=$(this).parent();
		var st=li.attr('treeStatus');
		var cat=li.attr('path');
		$('.liName').removeClass('currentt');
		li.addClass('currentt');
		
		var data={
			cat:	cat
		};
		switch(st){
			case "collapse":
				li.attr('treeStatus','expanded');
				
			break;
			case "expanded":
				li.attr('treeStatus','collapse');
			break;
			case "none":
				
			break;
		}
		
		refViaTree(data);
	});
}

function refViaTree(data){
	data.tab=$("#tab_container").attr('tab');
	Ajax('Project','getManageList',data,appendList);
}
</script>
</section>
