<h2 class="title">檔案分享</h2>
<form class="form">
	<?=Inp('upload','檔案上傳','<input type="file" multiple/>
	<button type="submit" class="bt">上傳</button>')?>
	<script>
	function fcb(){
		var files=$("#upload")[0].files;
		var sum=0;
		for(var i=0;i<files.length;i++){
			uploadFile(files[i],function(){
				sum++;

				if(sum==files.length){
					msgbox('上傳完成','success');
					$('#upload').val('');
				}
				refFileList();
			},-1,-1);
		}
	}
	</script>
</form>

<div id="uploadedFile"></div>
<script>
function refFileList(){
	Ajax('File','getFiles',{
		tid:	-1,
		fid:	-1
	},function(flist){
		var list=$("#uploadedFile").empty();
		var html='';
		flist.forEach(function(f){
			with(f){
				html='\
					<div class="uploadF" href="<?=mkLink('File','getFile')?>&id='+id+'">\
						<div class="fn">'+filename+'</div>\
						<div class="row">\
							<div class="ud">'+upload_time+'</div>\
							<div class="un">'+USERS[owner]+'</div>\
							<div class="fs">'+minByte(filesize)+'</div>\
						</div>\
					';
					<?
					$ad=getVal($_SESSION,'admin',0);
					$isAdmin=in_array($ad,[2,9]);
					?>
					if(<?=$isAdmin?1:0?> || owner==<?=getVal($_SESSION,'id',-1)?>){
						html+='<div class="delF" fid="'+id+'">X</div>';
					}
					
					html+='\
					</div>\
				';
				
				list.append(html);
			}
			
		});
		
		$('.uploadF').click(function(){
			window.open(this.getAttribute('href'));
		});
		
		$('.delF').click(function(e){
			e.stopPropagation();
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