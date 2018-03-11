<?
$page=Input::get('telPage');
function telPage($page){
	return mkLink('Tel','index').'&telPage='.$page;

}

function showUserName($uid){
	return '<script>document.write(USERS['.$uid.'] || "N/A")</script>';
}
?>
<link href="./media/css/Tel.css" rel="stylesheet"/>
<p></p>
<div id="dfoih" hidden>
<!--
	<li href="<?=mkLink('Tel','index')?>" id="ghk4j5fghk" <?=Cookie::get('lastClick')=='ghk4j5fghk'?"cur":''?>>
		<a onclick="document.cookie='lastClick=ghk4j5fghk'" href="<?=telPage('index')?>">
			客服工作
		</a>
	</li>
-->
	<li href="<?=telPage('list')?>" id="f5451g5j1" <?=Cookie::get('lastClick')=='f5451g5j1'?"cur":''?>>
		<a onclick="document.cookie='lastClick=f5451g5j1'" href="<?=telPage('list')?>">
			客服紀錄查詢
		</a>
	</li>
<?php
if(Session::get('admin')>0 || Session::get('telManager')==1){
?>
	<li href="<?=telPage('suggest')?>" id="hopkh0hf" <?=Cookie::get('lastClick')=='hopkh0hf'?"cur":''?>>
		<a onclick="document.cookie='lastClick=hopkh0hf'" href="<?=telPage('suggest')?>">
			常見問題管理
		</a>
	</li>
	<li href="<?=telPage('export')?>" id="d4fh9dfh" <?=Cookie::get('lastClick')=='d4fh9dfh'?"cur":''?>>
		<a onclick="document.cookie='lastClick=d4fh9dfh'" href="<?=telPage('export')?>">
			匯出客服紀錄
		</a>
	</li>

<?php
}
?>
</div>
<script>
TITLE('客服系統');

var menu=$('#menu ul li:first');
$('#dfoih li').each(function(){
	$(this).insertBefore(menu);
});
if($('#menu ul.first>li[cur]').length==0 || $('#menu ul.first>li[cur]').text()=='客服回報'){
	$('#menu ul.first>li:visible:first').attr('cur','a');
}
</script>
<script>

<?
if($page===null){
?>
var a=$("#menu a:visible:first");
a.click();
location.replace(a.attr('href'));
<?
}
?>

</script>

<div id="fpo">
	<div id="filter" class="row">
		<div hidden class="wh sel"></div>
		<div hidden class="wh sel"></div>
		<div class="wh" wh="<?=telPage('edit')?>" id="addd">
			<div class="whic add"></div>
			新增
		</div>
		<div class="wh" wh="<?=telPage('list')?>" id="listt">
			<div class="whic lookup"></div>
			查詢
		</div>
		
		<div hidden class="wh sel"></div>
		<div hidden class="wh sel"></div>
		<div hidden class="wh sel"></div>
		<div hidden class="wh sel"></div>
		
		
		
		<div id="sfdh" class="wh wh2" wh="finish=0" style="background:#FF2D55">
			<div class="whic three_dead"></div>
			尚未完成
		</div>
		<div id="oidfj" class="wh wh2" wh="1" style="background-color:#4CD964">
			<div class="whic new_add"></div>本日新增
		</div>
		
		<div class="wh" wh="./" id="backk">
			<div class="whic bback"></div>
			返回
		</div>
		<!--<div class="wh" wh="DATEDIFF(start_time,NOW())<=3 AND DATEDIFF(start_time,NOW())>=0 AND done=0">三日內新增</div>-->	
		
		<script>
		var fwh=$('#filter .wh[wh]');
		fwh.each(function(i){
			var t=$(this);
			t
			.attr('ii',i)
			.attr('txt',t.text().trim());
		}).click(function(){
			fwh.removeClass('sel');
			$(this).addClass('sel');
			location.href=this.getAttribute('wh');
		});
		$('#sfdh').click(function(){
			location.href='index.php?'+$.param({
				act:	'Tel',
				func:	'index',
				telPage:'list',
				oth:	'finish=0',
				kiufg:	'0',
				wh:		{
					cate_id:	-1,
					start_time:	'1900-01-01',
					end_time:	'2100-12-31'
				},
				dofih:	'ojih'
			});
		});
		$('#oidfj').click(function(){
			var dd=new Date();
			var today=[dd.getFullYear(),(dd.getMonth()+1).padLeft(2,'0'),dd.getDate().padLeft(2,'0')].join('-');
			location.href='index.php?'+$.param({
				act:	'Tel',
				func:	'index',
				telPage:'list',
				oth:	'1',
				kiufg:	'1',
				wh:		{
					cate_id:	-1,
					start_time:	today,
					end_time:	today
				},
				dofih:	'ojih'
			});
		});
		</script>
	</div>
</div>

<div>
<?require_once(__DIR__.'/'.$page.'.php');?>
</div>