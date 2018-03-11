
function TITLE(t){
	var b=$("#main_title");
	if(b.length==0){
		setTimeout(function(){
			TITLE(t);
		},1);
	}else{
		b.text(t.toString().trim());
	}
	
}

function addIcon(icon,todo){
	var b=$("#funcs");
	if(b.length==0){
		setTimeout(function(){
			addIcon(icon,todo);
		},1);
	}else{
		var i=$(
		'<div class="iicon">\
			<div class="ic" style="background-image:url(./media/images/'+icon+')"></div>\
		</div>');
		i.attr('img',icon);
		i.click(function(){
			if(typeof todo=="string"){
				location.href=todo;
			}else{
				todo();
			}
		});
		b.append(i);
	}
}

function setMenuStatus(show){
	if(show){
		$("#menu").addClass('show').data('show',true);
	}else{
		$("#menu").removeClass('show').data('show',false);
	}
}

$(function(){
	$('#menu_bt').click(function(){
		var m=$('#menu');
		var s=m.data('show');
		
		if(s){
			setMenuStatus(false);
		}else{
			setMenuStatus(true);
		}
	});
	

	$('.date.hasDatepicker').datepicker('destroy').addClass('txt').each(function(){
		this.value=this.value.replace(/\//g,'-');
		this.setAttribute('type',"date");
	});
	
	$('.datetime.hasDatepicker').datetimepicker('destroy').addClass('txt').each(function(){
		this.value=this.value.replace(/\//g,'-').replace(' ','T');
		this.setAttribute('type',"datetime-local");
	});
});	