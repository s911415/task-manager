<?
$id=intval($_GET['fid']);
$info=File::getInfo($id);
?>
<style>
.uploadF .fn{
	clear:none;
}
.comment{
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.ver{float:right;}
.ver .lastest{display:none}
.uploadF:first-child{
	float: none;
	display: block;
	width: auto;
	margin-bottom: 30px;
	clear:both;
}
.uploadF:first-child .ver .lastest{
	display:initial;
}
.uploadF:first-child .ver .cur_ver{
	display:none;
}
</style>
<h2 class="title left">檔案下載 <?=e($info->filename)?></h2>

<a href="<?=mkLink('File','folder',[
	'fid'	=>	$info->folder_id
])?>"><button class="bt right">回目錄</button></a>
<div id="revision" class="clear"></div>
<script>
(function(){
	var revData=<?=json_encode(
		File::getRev(
			$info
		)
	)?>;
	var list=$("#revision").empty();
	var rev=revData.length;
	revData.forEach(function(f){
		with(f){
			html='\
				<div class="uploadF" href="<?=mkLink('File','getFile')?>&id='+id+'">\
					<sup class="ver">(\
						<span class="lastest">最新版本</span>\
						<span class="cur_ver">Ver. '+(rev--)+'</span>\
					\)</sup>\
					\
					<div class="fn">\
						'+filename+'\
					</div>\
					<div class="clear"></div>\
					<div class="row comment">\
						備註: '+f.comment+'\
					</div>\
					\
					<div class="row">\
						<div class="ud">'+upload_time+'</div>\
						<div class="un">'+USERS[owner]+'</div>\
						<div class="fs">'+minByte(filesize)+'</div>\
					</div>\
				</div>\
			';
			
			list.append(html);
		}
	});
	

	$('.uploadF').click(function(){
		window.open(this.getAttribute('href'));
	});

})();
</script>