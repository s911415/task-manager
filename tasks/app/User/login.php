<div style="" id="pog">	
	<h2 style="text-align:center;margin-bottom:.5rem;margin-top:1rem">
		工作管理系統
	</h2>
	<h3 style="text-align:center;margin-top:.5rem;">
		請登入系統
	</h3>

	<div id="card">
		<div id="ava"></div>
			<style>
			#account,
			#password{
				font-size:1.25rem;
			}
			</style>
		<form class="form">
			<div class="flex" style="">
				<div style="-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;margin-right:.5rem">
					<?=Inp('account','','<input type="text" placeholder="Account" style="font-size:1.25rem"/>')?>
					<?=Inp('password','','<input type="password" placeholder="Password" style="font-size:1.25rem"/>')?>
				</div>
				<div style="width:4rem;height:4rem;">
					<button class="bt fill" style="min-width: 0 !important;
  width: 100% !important;
  height: 100%;
  background: no-repeat center;
  padding: 0 !important;" id="llooggiinn">登入</button>
				</div>
			</div>
			
		</form>

		<p class="r clear" style="margin-bottom:0">
			<a href="<?=mkLink('User','forget')?>">忘記密碼</a>
		</p>
	</div>
</div>

<style>
#pog{
	max-width:23rem;width:95%;margin:0 auto;
}
#card{
	background-color: #f7f7f7;
	padding: 1rem 1.5rem 1rem;
	margin: 0 auto;
	border-radius: 2px;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
body{background:#FFF;}

@media screen and (max-width:490px){
	#llooggiinn{
		border-radius:.5rem;
	}
}
#row_password{
	margin-top:.5rem;
}
</style>
<script>
function fcb(){
	var d=getVal('account|password');
	Ajax('User','login',d,function(d){
		if(d){
			location.href='./';
		}else{
			msgbox('帳號或密碼錯誤','error');
		}
	});
}
</script>
<script>
function forget(){
	
}
</script>