<?
	$type=getVal($_GET,'type','checkbox');
	$target=getVal($_GET,'target');
?>
<h2 class="c title">選擇使用者</h2>
<div class="row">
	<input type="search" class="txt" placeholder="請輸入姓名" id="search"/>
</div>
<style>
#list{
	display:-webkit-box;
	display:-webkit-flex;
	display:-ms-flexbox;
	display:flex;
	-webkit-flex-flow:row wrap;
	    -ms-flex-flow:row wrap;
	        flex-flow:row wrap;
	  -webkit-box-pack: start;
	  -webkit-justify-content: flex-start;
	      -ms-flex-pack: start;
	          justify-content: flex-start;
}
.user{
	width: calc(100% / 3 - 0.5rem);
	padding: 10px 18px;
	border: 1px solid #CCC;
	background: #EEE;
	margin:4px;
	margin: .25rem;
	border-radius: 3px;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	display:-webkit-box;
	display:-webkit-flex;
	display:-ms-flexbox;
	display:flex;
	box-sizing:border-box;
}
.user .nam{
  padding-left: 8px;
  -webkit-box-flex: 1;
  -webkit-flex: 1;
      -ms-flex: 1;
          flex: 1;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
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
<div id="list"></div>
<div class="footfunc"></div>
<div class="row c footfunc">
	<button class="bt" id="submit">確定</button>
	<button onclick="init()">重設</button>
	<button onclick="window.close()">關閉</button>
</div>
<script>
var ids=location.hash.replace(/^#+/,'').split(',');
(function(){
	function ref(){
		var list=$("#list").empty();
		var searchVal=$("#search").val().trim().toLowerCase();
		for(var i in USERS){
			if(searchVal!="" && USERS[i].toLowerCase().indexOf(searchVal)==-1) continue;
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
$("#submit").click(function(){
	var p=[];
	$('.uid:checked').each(function(){
		p.push(this.value);
	});
	var o=window.opener.$("#<?=$target?>");
	o.val(p.join(','));
	o.change();
	window.close();
});
</script>