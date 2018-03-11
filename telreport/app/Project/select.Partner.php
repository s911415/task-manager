<?
	$type=getVal($_GET,'type','checkbox');
	$target=getVal($_GET,'target');
?>
<h2 class="c title">選擇成員</h2>
<style>
#list{
	display:-webkit-box;
	display:-webkit-flex;
	display:-ms-flexbox;
	display:flex;
	-webkit-flex-flow:row wrap;
	    -ms-flex-flow:row wrap;
	        flex-flow:row wrap;
	  -webkit-justify-content: space-around;
	      -ms-flex-pack: distribute;
	          justify-content: space-around;
}
.user{
	width: calc(33% - 50px);
	padding: 10px 18px;
	border: 1px solid #CCC;
	background: #EEE;
	margin:4px 0;
	margin: .25rem 0;
	border-radius: 3px;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}
.user .nam{
	display:-webkit-box;
	display:-webkit-flex;
	display:-ms-flexbox;
	display:flex;
	padding-left:8px;
	-webkit-box-flex:1;
	-webkit-flex:1;
	    -ms-flex:1;
	        flex:1;
}
</style>
<div id="list"></div>
<div class="row c">
	<button class="bt" id="submit">確定</button>
	<button onclick="init()">重設</button>
	<button onclick="window.close()">關閉</button>
</div>
<script>
(function(){
	var list=$("#list").empty();
	for(var i in USERS){
		list.append('\
			<label class="user l">\
				<input type="<?=$type?>" name="uid[]" class="uid" value="'+i+'"/>\
				<div class="nam">'+(USERS[i])+'</div>\
			</label>\
		')
	};
})();

function init(){
	var ids=location.hash.replace(/^#+/,'').split(',');
	ids.forEach(function(id){
		$('.uid[value="'+id+'"]').prop('checked',true);
	});
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