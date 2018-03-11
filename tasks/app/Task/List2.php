<?php
require('List.php');
?>
<style>
.row.resp,.track{display:block;}
.project_name{
	width:120px;
}
#taskAdd,.report{display:none}
</style>
<script>
$('.title').html($('.title').html().replace('我的工作','工作進度追蹤'));
$('#asopoasg').attr('txt', "被退件且已送回")
$('#resp_id').prop('checked',false);
fedit=true;
</script>