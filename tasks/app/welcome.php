<?if(!isset($_SESSION['id'])){
loc(mkLink('User','login'));
}else{
loc(mkLink('Task','List'));
?>

<script>
/*
$(function(){
	Ajax('User','getNotice',null,function(d){
		if(d){
			var TC=d.modTaskCount;
			if(TC){
				msgbox('距離您上次登入<?=$_SESSION['last_login']?>後，<br/>已有'+TC+'個工作有變更，請前往工作清單查看。','success',function(){
					jmpFirst();
				});
			}else{
				jmpFirst();
			}
		}else{
			jmpFirst();
		}
	});
	
});
*/
function jmpFirst(){
	var a=$("#menu a:first").attr('href');
	if(a){
		location.replace(a);
	}
}
</script>

<?}?>