<div style="width:20rem;margin:0 auto">
	<p>&nbsp;</p>
	<h3 style="text-align:center">
		忘記帳號或密碼了嗎？
	</h3>

	<div id="card">
		<div id="ava"></div>
		<p>

		</p>
		<form class="form">
			<?=Inp('email','','<input type="email" placeholder="E-Mail"/>')?>
			<div class="row flex">
				<div style="-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;margin-right:.25rem">
					<button class="bt fill">傳送帳號密碼</button>
				</div>
				<div style="-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;margin-left:.25rem">
					<a href="<?=mkLink('User','login')?>">
						<button class="fill" type="button">取消並返回上頁</button>
					</a>
				</div>
			</div>
			

		</form>

	</div>
</div>

<style>
#card{
	background-color: #f7f7f7;
	padding: 24px 28px;
	padding: 1.5rem 1.75rem;
	margin: 0 auto;
	border-radius: 2px;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
body{background:#FFF;}
</style>
<script>
function fcb(){
	var d=getVal('email');
	Ajax('User','forget',d,function(d){
		if(d){
			msgbox('您的帳號及密碼已經寄到您的信箱。','success');
		}else{
			msgbox('查無此信箱','error');
		}
	});
}
</script>
<script>
function forget(){
	
}
</script>