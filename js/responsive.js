// JavaScript Document
var interval_active = 1;

$(document).ready(function() {resize_response();});

	
	window.onscroll = function (e) {
		
	largura_body_scroll();

    //BEGIN BACK TO TOP
        if ($(this).scrollTop() < 200) {
            $('#totop') .fadeOut();
        } else {
            $('#totop') .fadeIn();
        }
    //END BACK TO TOP
//	 if(document.getElementById("mais_resultados") !== null){	
//	 	if(interval_active==0){
//			lock = document.getElementById("permite_carregar").value;
//			//trava o carregamento
//			if(lock > 0){
//				call_carrega_listagem('roll'); 
//			}
//		}
//	 }

 }
 
 interval_call_carrega = setInterval(function(){    
 	lock = $("#permite_carregar").val();
	if(lock > 0){
		call_carrega_listagem('int');  
	}

}, 1000);
 
 function call_carrega_listagem(origem){
	 //alert(origem);
		 if(document.getElementById("mais_resultados") !== null){	 
			//trava o carregamento
			//  CARREGA LISTAGEM
			var docViewTop = $(window).scrollTop();
			var docViewBottom = docViewTop + $(window).height();
			var elemTop = $(document.getElementById("mais_resultados")).offset().top;
			var elemBottom = elemTop + $(document.getElementById("mais_resultados")).height();
			 
			if ((elemBottom >= docViewTop) && (elemBottom <= docViewBottom)  ){
				//trava o carregamento
				lock = $("#permite_carregar").val();
				if(lock > 0){
					$("#permite_carregar").val('0');
					carregar_resultados();
				}
					
			}
			else{
				//clearInterval(interval_call_carrega);
				//interval_active=0;
			}
		}
		else{
			//clearInterval(interval_call_carrega);
			//interval_active=0;
		}
}
  
 
 $('#totop').on('click', function(){
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });


function largura_body_scroll(){

	wd_janela = $(window).outerWidth(true);
	scroll_lateral = $(window).scrollLeft();
	soma= scroll_lateral+wd_janela;
	$('html, body').css('width', soma+'px');
	//$('.logo-text').html(wd_janela+" | "+scroll_lateral+" | "+soma);
}


function resize_response(){
	$('html, body').css('width', 'auto');
	//recupera tamanho do navegador
	w = window.outerWidth; 
	wd_janela = $(window).outerWidth(true);
	//$('.logo-text').html(w+" | "+wd_janela+" | ");
	if(wd_janela>=756){ //783){
		
		//sempre exibe o menu
		$('#sidebar_resp').addClass("display_block"); 
		$('#sidebar_resp').addClass("overflow_block"); 
		$('#sidebar_resp').removeClass("in");
		//headr adm - itens do perfil abre para a direita
		$('#ul_hd_adm').addClass('pull-right').removeClass('pull-left');
		
		$('#div_notificacoes').removeClass("div_notificacoes_xs").addClass("div_notificacoes_lg");
	}
	else{
		//tela pequena - menu comeca fechado
		$('#bt_hd_resp').addClass('collapsed');
		if(document.getElementById("sidebar_resp") !== null){
			document.getElementById('sidebar_resp').setAttribute("style","height:0px");
		}
		$('#sidebar_resp').addClass("collapse");
		$('#sidebar_resp').removeClass("display_block");
		$('#sidebar_resp').removeClass("overflow_block");
		
		//headr adm - itens do perfil abre para a esquerda
		$('#ul_hd_adm').removeClass('pull-right').addClass('pull-left');
		
		$('body').removeClass('left-side-collapsed');	
		
		$('#div_notificacoes').removeClass("div_notificacoes_lg").addClass("div_notificacoes_xs");
	}	
	
}



