<?php
require('List.php');
?>
<style>
.row.resp,.judge{display:block;}
.project_name{
	width:120px;
}
#taskAdd,.report{display:none !important}
</style>
<script>
$('.wh').each(function(w){
	var t=$(this);
	t.attr('wh',t.attr('wh').replace(/done=(\d+)/g,'1'));
});
$('.title').html($('.title').html().replace('我的工作','稽核工作'));
$('#resp_id').prop('checked',false);
$('#track').prop('checked',true);
fedit=true;
</script>