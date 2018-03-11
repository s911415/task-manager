<script>TITLE('登入');</script>
<center class="w">
	<h3 class="title">請先登入</h3>
	
	<p>
		<img src="./media/images/man.png"/>
	</p>
	
	<form class="form">
		<?=Inp('account','','<input type="text" placeholder="帳號"/>')?>
		<?=Inp('password','','<input type="password" placeholder="密碼"/>')?>
		
		<button class="bt fill">登入</button>
	</form>
	<script>
	function fcb(){
		var d=getVal('account|password');
		Ajax('User','login',d,function(d){
			if(d){
				msgbox('登入成功','success',function(){
					location.href='./';
				});
			}else{
				msgbox('帳號或密碼錯誤','error');
			}
		});
	}
	</script>
</center>