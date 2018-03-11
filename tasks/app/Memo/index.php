<div class="row">
    <h2 class="title left" style="margin: .25rem 0;" id="fdiho">
        個人備忘錄
    </h2>
    <script>
        $(function () {
            setTimeout(function () {
                var t = $("#fdiho");
                TITLE(t.text().trim());
                t.hide();
            }, 200);

        });
    </script>

    <div class="right" id="spp">

    </div>


    <div class="clear"></div>
</div>
<style>
    .bk {
        width: 5rem;
    }

    .bb {
        width: 1rem;
        height: 1rem;
        display: inline-block;
        margin-right: .5rem;
        vertical-align: top;
        border: 1px solid #CCC;
    }

    .memo {
        transition: .5s;
        min-height: 0px;
    }

    .memo.active {
        display: block;
    }

    .memo .project_name {
        transition: .5s;
    }

    .memo.active .project_name {
        font-size: 12px;
        font-weight: bold;
    }

    .memo.active .row.action {
        width: 100%;
        text-align: right;
    }

    .memo.active {
        cursor: inherit;
    }
</style>
<div class="clear"></div>
<?php /*
<div id="fpo">
	<div id="filter" class="row">
		<div class="wh " wh="DATEDIFF(deadline_time,NOW())<=1 AND done=0"> <!-- AND DATEDIFF(deadline_time,NOW())>=0-->
			<div class="whic today_dead"></div>
			本日截止及逾期
		</div>
		<div class="wh " wh="done=2"> <!-- AND DATEDIFF(deadline_time,NOW())>=0-->
			<div class="whic deny"></div>
			被退件
		</div>
		<div class="wh" onclick="addTask()" wh="0" id="taskAdd">
			<div class="whic add"></div>
			新增工作
		</div>
		<div class="wh sel" wh="DATEDIFF(deadline_time,NOW())<=3 AND DATEDIFF(deadline_time,NOW())>=0 AND done=0">
			<div class="whic three_dead"></div>
			三日內截止
		</div>
		<div class="wh" wh="done=0">
			<div class="whic todo"></div>
			全部未完成
		</div>
		<div class="wh" wh="DATEDIFF(start_time,NOW())<=3 AND DATEDIFF(start_time,NOW())>=0 AND done=0">
			<div class="whic new_add"></div>三日內新增
		</div>
		<div class="wh" wh="DATE(finish_time)=CURDATE() AND progress=100">
			<div class="whic adone"></div>
			本日完成
		</div>
		<div class="wh" wh="done=1">
			<div class="whic adone"></div>
			全部完成
		</div>
		<div class="wh" wh="own_id=<?=Session::get('id')?>" style="font-size:14px">
			<div class="whic new_add" style="margin-top:3px;"></div>
			我建立的<br/>工作
		</div>
		<!--<div class="wh" wh="DATEDIFF(start_time,NOW())<=3 AND DATEDIFF(start_time,NOW())>=0 AND done=0">三日內新增</div>-->	
		
		
		<div class="col hide">
			<label>其他</label>
			<label class="nos">
				<input type="checkbox" value="<?=$_SESSION['id']?>" id="resp_id" 
				<?
				//$allowAll=[9,2,1];
				$allowAll=[9,2,1];
				if(false || !in_array($_SESSION['admin'],$allowAll)){
					echo 'checked';
				}
				?>
				/>
				只顯示我負責的工作
			</label>
		</div>
		<script>
		function showMore(){
			$('#more').addClass('show');
			$("#sless").css('display','block');
			$("#smore").hide();
		}
		function hideMore(){
			$('#more').removeClass('show');
			$("#sless").hide();
			$("#smore").css('display','block');
		}
		</script>
		<div class="hide">
		<?=Inp('track','track','<input type="checkbox" value="'.Session::get('id').'"/>')?>
		</div>
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
			ref();
			location.hash='#wh'+$(this).attr('ii');
		});
		</script>
	</div>
</div>
*/ ?>
<div class="row">

    <div id="more">
        <div class="flex">
            <div class="row">
                <?= Inp('archive', '\\包含已封存', '<input type="checkbox" onchange="ref()"/>', false) ?>
            </div>

            <div class="row">
                <?= Inp('search', '', '<input type="search" class="txt" placeholder="Search..." oninput="ref()"/>') ?>

            </div>
        </div>
    </div>
    <div class="row clear">
        <a href="#" onclick="return showMore()" id="smore">Show More</a>
        <a href="#" onclick="return hideMore()" id="sless" style="">Show Less</a>
    </div>
</div>
<style>
    #headdd .task {
        background: none;
        box-shadow: none;
        font-size: .8rem;
        color: #888;
        font-weight: bold;
        padding-top: 0;
        padding-bottom: .5rem;
    }

    #headdd .task::before {
        display: none;
    }

    #headdd .task .row {
        margin-top: 4px;
        margin-bottom: 4px;
    }

    .memo .title {
        display: block;
        max-height: 1.5em;
    }

    .act_icon {
        max-width: 40px;
    }

    .row .title {
        transition: .5s;
        min-height: 1em;
    }

    .memo.active .title {
        min-height: 3em;
        background-color: #fff8e1;
        padding:.5em;
        max-height: 999999999999px;
    }
</style>
<div id="headdd">
    <div class="task">
        <div class="row project_name">日期</div>
        <div class="row title">紀事</div>
        <div class="row action">
            功能&nbsp;
        </div>
    </div>
</div>

<div id="list"></div>
<!--

<table class="table" style="table-layout:fixed;">
	<thead>
		<tr>
			<th>標題</th>
			<th width="85">負責人</th>
			<th width="85">PM</th>
			<th width="60">期限</th>
			<th width="75">執行狀態</th>
			<th width="165"></th>
		</tr>
	</thead>
	<tbody id="list"></tbody>
</table>
-->
<script>
    function ref() {
        var Data = getVal('archive|search');
        if (Data.archive) delete Data.archive;

        var list = $("#list").empty();
        list.append('<div class="waitIcon"></div>');
        Ajax('Memo', 'getMyMemo', Data, function (d) {
            var html = '';
            list.empty();
            d.forEach(function (b) {
                with (b) {
                    /*
                     var dd=deadline_time.split('/');
                     var ddd=$('<span>'+description+'</span>').text();
                     */
                    html = $(
                        '<div class="task memo" data-mid="' + id + '">\
						<div class="row project_name">' + created_at + '</div>\
						<div class="row title" data-mid="'+id+'">' + memo + '</div>\
						\
						<div class="row action">\
							<div class="act_icon delete" title="刪除"></div>\
							<div class="act_icon track" title="完成封存"></div>\
						</div>\
					</div>'
                    );
                }
                list.append(html);
            });
            if (d.length == 0) {
                list.append('<div style="  margin: 1rem 0;text-align: center;">你的備忘錄是空的</div>');
            }
            bindEvent();
        });
    }
    function bindEvent() {
        var memos = $('.memo');
        memos.find('.delete').click(function (e) {
            var t = $(this);
            var id = t.parents('.memo').data('mid');
            if (confirm('您確定要刪除此紀錄')) {
                Ajax('Memo', 'delMemo', {
                    id: id
                }, function () {
                    ref();
                    msgbox('刪除完成');
                });
            }

            e.stopPropagation();
        });
        memos.find('.track').click(function (e) {
            var t = $(this);
            var id = t.parents('.memo').data('mid');
            Ajax('Memo', 'archiveMemo', {
                id: id
            }, function () {
                msgbox('封存成功');
                ref();
            });

            e.stopPropagation();
        });
        memos.bind('activeModeChange', function (e) {
            var t = $(this), nextMode = !t.data('curActMode');

            switch (nextMode) {
                case true:
                    t.addClass('active');
                    t.find('.title').prop('contenteditable', true).focus();
                    break;
                case false:
                    t.removeClass('active');
                    t.find('.title').prop('contenteditable', false);
                    break;
            }

            t.data('curActMode', nextMode);
        }).data('curActMode', !1);

        memos.click(function () {
            var t = $(this), active = memos.filter('.active');
            if(t[0]!=active[0]){
                active.trigger('activeModeChange');
                t.trigger('activeModeChange');
            }

        });
        memos.find('.title').bind('blur', function(e){
            var memo=$(this);
            Ajax('Memo', 'editMemo', {
                id: memo.data('mid'),
                memo:   memo.html()
            }, function(){
                //msgbox('儲存成功', 'success');
            })
        })
    }


    $(function () {
        ref();
    });
    function showMore() {
        $('#more').addClass('show');
        $("#sless").css('display', 'block');
        $("#smore").hide();
    }
    function hideMore() {
        $('#more').removeClass('show');
        $("#sless").hide();
        $("#smore").css('display', 'block');
    }
</script>

<style>
    <?
    $arr=[
        9
    ];
    if(in_array(Session::get('admin')*1,$arr)){
    ?>
    .edit, .delete {
        display: inline-block !important;
    }

    <?
    }
    ?>
</style>