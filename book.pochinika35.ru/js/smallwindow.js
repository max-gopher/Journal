/* Открываем модальное окно: */
function open_popup(box) { 
  $("#background").show(); 
  $(box).centered_popup(); 
  $(box).delay(100).show(1); 
} 
 
/* Закрываем модальное окно: */
function close_popup(box) { 
	$(box).hide(); 
	$("#background").delay(100).hide(1);
} 
 
$(document).ready(function() { 
  /* Позиционируем блочный элемент окна по центру страницы: */
  $.fn.centered_popup = function() { 
    this.css('position', 'absolute'); 
    this.css('top', ($(window).height() - this.height()) / 2 + $(window).scrollTop() + 'px'); 
    this.css('left', ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + 'px'); 
  }; 
 
});

function addAndCloseGaran(idtov){
	var garan = $("#garan" + idtov).val();
	$.post("/inc/addinchek.php?garan", {garan : garan, idtov : idtov}, function(data){
		if(data.length>0){
			$("#bask").html(data);
		}
	});
	close_popup('#modal_window' + idtov);
}

function addAndCloseSn(idtovsn){
	var sn = $("#sn" + idtovsn).val();
	$.post("/inc/addinchek.php?sn", {sn : sn, idtovsn : idtovsn}, function(data){
		if(data.length>0){
			$("#bask").html(data);
		}
	});
	close_popup('#modal_window_sn' + idtovsn);
}