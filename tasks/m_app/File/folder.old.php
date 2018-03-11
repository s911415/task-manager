<div id="menuList"></div>

<script>
function ref(){
	Ajax('Folder','getFolders',{
		fid:	<?=getVal($_GET,'fid',0)?>
	},function(d){
		var menuList=$('#menuList').empty();
		menuList.append(extractMenu(d));
		
		menuList.find('.folderName').click(function(e){
			var f=$(this.parentNode).find('>.folder');
			var show=f.attr('status')=='show';
			
			if(!show){
				f.attr('status','show');
			}else{
				f.attr('status','hide');
			}
		});
	});
}
ref();
</script>