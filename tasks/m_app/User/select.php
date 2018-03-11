<?
	$type=getVal($_GET,'type','checkbox');
	$target=getVal($_GET,'target');
?>
<script>TITLE('選擇使用者')</script>

<style>
.user{
	display: block;
	padding: 10px 18px;
	border: 1px solid #CCC;
	background: #FFF;
	margin: 3px 0;
	border-radius: 3px;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
.user .nam{
	display:inline-block;
	padding-left:8px;
}
.footfunc{
	height:45px;
}
.row.footfunc{
	position:fixed;
	bottom:0;
	width:100%;
	z-index:15;
	background:#FFF;
	margin-bottom:0;
}
</style>
<div class="w">
	<div id="list"></div>
</div>
<div class="footfunc"></div>
<script>
var ids=location.hash.replace(/^#+/,'').split(',');
(function(){
	function ref(){
		var list=$("#list").empty();
		for(var i in USERS){
			if(<?=$type=='radio'?'true':'false'?> && ids.indexOf(i)!=-1) continue;
			list.append('\
				<label class="user l">\
					<input type="<?=$type?>" name="uid[]" class="uid" value="'+i+'"/>\
					<div class="nam">'+(USERS[i])+'</div>\
				</label>\
			')
		};
	}
	
	$("#search").bind('input',ref);
	ref();
})();

function init(){
	$('.uid').prop('checked',false);
	ids.forEach(function(id){
		$('.uid[value="'+id+'"]').prop('checked',true);
	});
	var html=$('html');
	var w=Math.min(html.width()+50,screen.width/3);
	var h=Math.min(html.height()+100,screen.height/2);
	
	window.resizeTo(w,h);
}
$(init);
function submit(){
	var p=[];
	$('.uid:checked').each(function(){
		p.push(this.value);
	});
	var o=window.opener.$("#<?=$target?>");
	o.val(p.join(','));
	o.change();
	window.close();
};
addIcon('mobile/ok.png',submit);
addIcon('mobile/del.png',function(){
	window.close();
});
</script>