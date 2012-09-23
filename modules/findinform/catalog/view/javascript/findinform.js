$(document).ready(function(){
    $('.success, .warning, .attention, .information').remove();
    var infomessage = $('div.findinform-message').text();
    if (infomessage!='') {
	$('#notification').html('<div class="success" style="display: none;">' + infomessage + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
	
	$('.success').fadeIn('slow');
	
	$('html, body').animate({ scrollTop: 0 }, 'slow'); 				
    }
    $(document).bind("keydown","Alt+Ctrl+e",function(){
	var t = '';
	if(window.getSelection){
	    t = window.getSelection();
	}else if(document.getSelection){
	    t = document.getSelection();
	}else if(document.selection){
	    t = document.selection.createRange().text;
	}

	if(t!=''){
	    $(".findinform-sel").text(t);
	    $(".findinform-modal").simpleModal().trigger("show");
	}
	return false;
    });
});


				
		