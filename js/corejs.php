<!--LOADING SCRIPTS FOR CHARTS-->
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-1.10.2.min.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-ui.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/bootstrap.min.js"></script>

<script src="<?php echo getenv('CAMINHO_SITE');?>/js/geral.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/alerts/jquery.alerts.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/bootstrap-hover-dropdown.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery.menu.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/responsive.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/css3-animate-it.js"></script>
<script src="<?php echo getenv('CAMINHO_SITE');?>/js/bootstrap-multiselect.js"></script>
<script>
// JavaScript Document
<?php
$welcome = "Bem vindo ao MECOB";
if(!empty($cond_nome)){$welcome.=" - ".$cond_nome;}
$array_text_header[] = $welcome;
$array_text_header[] = "Padronização e autonomia a um clique de distância";
$array_text_header[] = "Controle total de compra e venda de lotes";
$array_text_header[] = "Geração de acordos e registros de ocorrências";
$array_text_header[] = "Emissão e envio de boletos";
$array_text_header[] = "Integração com instituição bancária";
$array_text_header[] = "Segurança, Rastreabilidade e Auditoria ";
$array_text_header[] = "Tudo isso e muito mais para automatizar os seus processos";
##### NÃO USAR TEXTOS MUITO GRANDES === AO INSERIR UM NOVO, TESTAR  O RESPONSIVO


$json_array_text_header = json_encode($array_text_header);
echo "var my_array_header = ". $json_array_text_header . ";\n";
echo "var cont_text_header = ". count($array_text_header) . ";\n";
?>


var text_header_atual = 0;
$(document).ready(function() {
    exibe_texto_header(my_array_header[text_header_atual]);

    myInterval = setInterval(function() {
        text_header_atual++;
        if (text_header_atual >= cont_text_header) {
            text_header_atual = 0
        }
        exibe_texto_header(my_array_header[text_header_atual]);

    }, 6000);

});

function exibe_texto_header(texto) {
    tamanho_div_img = $(window).outerWidth(true) - 300 - 331;
    $("#header-cobre-text").animate({
        width: tamanho_div_img
    }, 1);
    $('#header-text').html(texto);
    $("#header-cobre-text").css("width", tamanho_div_img);
    $("#header-cobre-text").animate({
        width: '10px'
    }, 4000);
}

var div_loading_autocp =
    '<div id="div_loading_autocp" class="row loading_something  hidden"><img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" /></div>';



function show_hide_notificacoes(){
	if ($('#div_notificacoes').hasClass("showed")) {
        $('#div_notificacoes').fadeOut();
        $('#div_notificacoes').removeClass('showed');
    } else {
        $('#div_notificacoes').fadeIn();
        $('#div_notificacoes').addClass('showed');
    }
}	

var control_see_notific = 0;
var primeira_see_notific = 0;

function ver_notificacoes() {

    if (primeira_see_notific == 0) {
        primeira_see_notific = 1;
        load_more_notificacoes();
    }
    

	show_hide_notificacoes();
    if (control_see_notific == 0) {
        control_see_notific = 1;
        resize_response();

        $('.badge-bk_blue_light_notifc').addClass('bk_blue_light_notifc');
        $('#badge-notificacoes').addClass('hidden');

        $.getJSON('<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=see&pessoas_id=".$_SESSION["id"];?>', {
            ajax: 'true'
        }, function(j) {});
    }
}

function load_more_notificacoes() {
    $('#item_notificacoes_load').addClass('hidden');
    $('#item_notificacoes_loading').removeClass('hidden');
    ja_carregados = $('#ct_notifc_exibidos').val();
    $.getJSON(
    '<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=load_more&pessoas_id=".$_SESSION["id"];?>', {
        ja_carregados: ja_carregados,
        ajax: 'true'
    }, function(j) {

        cont_novos = 0;
        novos = "";
        //alert(JSON.stringify(j));
        for (var i = 0; i < j.length; i++) {
            //open tr
            notificacao_aux = JSON.stringify(j[i]);
            novos += '<div class="col-md-12 ">';

            if (j[i].link != 'N' && j[i].link != null) {
                novos += ' <a href="' + j[i].link + '">  ';
            }

            novos += '<div class="item_notificacoes ';
            if (j[i].visualizado == 'N') {
                novos += ' bk_blue_light_notifc ';
            }

            novos += ' "> ';
            novos += '<div class="col-xs-10 pd-tp-3">' + j[i].descricao + ' </div> ';
            novos += '<div id="notf_alerta_' + j[i].id + '" class="col-xs-1 pd-tp-3">';

            if (j[i].concluido == 'S') {
                novos +=
                    ' <i class="fa fa-thumbs-up fs-19 blue_light" data-toggle="tooltip" data-placement="left" title="Concluído em ' +
                    ConverteData(j[i].dt_concluido) + '" data-original-title="Concluído em ' + ConverteData(j[i]
                        .dt_concluido) + '"> </i> ';

            } else {
                novos +=
                    ' <i  class="fa fa-exclamation-triangle red_light pointer" data-toggle="tooltip" data-placement="left" title="Marcar como Concluído" data-original-title="Marcar como Concluído"   onClick="concluir_alerta(' +
                    j[i].id + ');"   > </i> ';
            }

            novos += '</div> ';
            novos += ' </div> ';

            if (j[i].link != 'N') {
                novos += ' </a>  ';
            }
            novos += ' </div> ';

            ja_carregados++;
            cont_novos++;
        }
        if (ja_carregados == 0) {
            novos = "<div class='item_notificacoes' ><div class='col-xs-12 ac'>Nenhum alerta novo! <br/> <a href='<?php echo $link;?>/alertas'>Visualizar todos os alertas</a> </div> </div>";
        }

        $('#item_notificacoes_loading').addClass('hidden');
        $('#insert_notific').append(novos);

        if (cont_novos == 10) {
            $('#item_notificacoes_load').removeClass('hidden');
        }

        document.getElementById("ct_notifc_exibidos").value = ja_carregados;
    });

}


function concluir_alerta(id_alerta) {
    event.preventDefault();
    jConfirm('Deseja marcar este aerta como concluído?', 'Confirma', function(r) {
        if (r) {
            $.getJSON("<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=alerta_concluido";?>", {
                id_alerta: id_alerta
            }, function(result) {
                if (result == 1) {

                    $('#notf_alerta_' + id_alerta).html(
                        '<i class="fa fa-thumbs-up fs-19 blue_light" data-toggle="tooltip" data-placement="left" title="Marcado como Concluído agora mesmo."> </i>'
                        );

                    jAlert('Informação atualizada!', 'Bom trabalho!', 'ok');

                } else {
                    jAlert('Erro ao marcar como concluído.', 'Não foi possível salvar as alterações!',
                        'alert');
                }
            });
        } else {
            jAlert('Alerta ainda pendente.', 'Ação cancelada', 'ok');
        }
    });
}
</script>

<?php
if (0 && strpos($_SERVER['PHP_SELF'], 'mesistema') == false) {
	
	?>
<script type="text/javascript">
window.smartlook || (function(d) {
    var o = smartlook = function() {
            o.api.push(arguments)
        },
        h = d.getElementsByTagName('head')[0];
    var c = d.createElement('script');
    o.api = new Array();
    c.async = true;
    c.type = 'text/javascript';
    c.charset = 'utf-8';
    c.src = '//rec.smartlook.com/recorder.js';
    h.appendChild(c);
})(document);
smartlook('init', '99b070cad563335f9c16cf740c6d69372fc97fee');
</script>
<?php  }   ?>