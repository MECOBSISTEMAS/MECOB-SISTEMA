<?php 

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active = "cadastros"; 
$layout_title = "MECOB - Alertas";
$tit_pagina = "Alertas";	
$tit_lista = " Lista de alertas";	

$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';
include($raiz."/partial/html_ini.php");

include_once($raiz."/inc/util.php");

?>

<div>
    <!--BEGIN BACK TO TOP-->
    <a id="totop" href="#"><i class="fa fa-angle-up"></i></a>
    <!--END BACK TO TOP-->
    <!--BEGIN TOPBAR-->
    <?php include($raiz."/partial/header.php");?>
    <!--END TOPBAR-->
    <div id="wrapper">
        <!--BEGIN SIDEBAR MENU-->
        <?php include($raiz."/partial/sidebar_adm.php");?>
        <!--END SIDEBAR MENU-->
        <div id="page-wrapper">
            <!--BEGIN TITLE & BREADCRUMB PAGE-->
            <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">
                        <?php echo $tit_pagina; ?></div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active"><?php echo $tit_pagina;?></li>
                </ol>
                <div class="clearfix">
                </div>
            </div>
            <!--END TITLE & BREADCRUMB PAGE-->
            <!--BEGIN CONTENT-->
            <div class="page-content">
                <div id="tab-general">
                    <div class="row mbl">
                        <div class="col-lg-12">
                            <div class="panel panel-bordo" style="background:#FFF;">
                                <div class="panel-heading"><?php echo $tit_lista;?></div>
                                <div class="panel-body">
                                    <?php 
include($raiz."/adm/alertas/filtros_alertas.php");	?>
                                    <div id="linha_totais"></div><br />
                                    <div id="listagem">
                                        <table id="listagem_parcelas" class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th id="th_remetente" class="pointer "
                                                        onclick="ordenar('nome_remetente');">
                                                        Remetente <i class="fa fa-arrow-circle-up fl-rg ico_ordem"></i>
                                                    </th>
                                                    <th id="th_mensagem" class="hidden-xs hidden-sm">
                                                        Mensagem
                                                    </th>
                                                    <th id="th_link" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('link');">
                                                        Link
													</th>
													<th id="th_data" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('dt_prazo');">
                                                        Prazo
                                                    </th>
                                                    <th id="th_concluido" class="pointer hidden-xs hidden-sm">
                                                        Concluído
                                                    </th>
                                                    <th id="th_data" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('data_alerta');">
                                                        Data do alerta
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_parcelas">
                                                <tr>
                                                    <td id="td_carregando" colspan="15">Carregando Alertas</td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <div id="mais_resultados"></div>
                                        <div id="loading_resultados"
                                            style="display:none; text-align:center; color:#667;">
                                            <h4> <img src="<?php echo $link."/imagens/loading_circles.gif";?>"
                                                    width="18px;" /> &nbsp;Carregando</h4>
                                        </div>
                                        <input id="cont_exibidos" type="hidden" value="0">
                                        <input id="permite_carregar" type="hidden" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--END CONTENT-->
            <!--BEGIN FOOTER-->
            <?php include($raiz."/partial/footer.php");?>
            <!--END FOOTER-->
        </div>
        <!--END PAGE WRAPPER-->
    </div>
</div>

<!-- fim cadastro de pessoas-->
<?php include $raiz."/js/corejs.php";?>
<script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js" />
</script>
<script src="<?php echo $link;?>/js/jquery.validate.js" />
</script>
<script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
<script>
var filtro_per_ini = "";
var filtro_per_fim = "";
var filtro_status = "";

var total_results = 0;


var filtrar = 0;

var order = "agendada";
var ordem = "asc";

var delay_busca;

$(function() {
    <?php 
	if(isset($ini_filtro) && $ini_filtro){
		?> filtrar_fields();
    <?php }
		else{
			?>carregar_resultados();
    <?php }?>
    carregar_totais();
    $('[data-toggle="tooltip"]').tooltip();
    $("#filtro_per_ini").mask("99/99/9999");
    $("#filtro_per_ini").datepicker({
        dateFormat: 'dd/mm/yy'
    });

    $("#filtro_per_fim").mask("99/99/9999");
    $("#filtro_per_fim").datepicker({
        dateFormat: 'dd/mm/yy'
    });
});


function limpa_filtros() {
    $('#filtro_per_ini').val('');
    $('#filtro_per_fim').val('');
    $('#filtro_status').val('');

    filtrar = 0;
    filtrar_fields();
}

function filtrar_fields() {
    filtro_status = $('#filtro_status').val();
    filtro_per_ini = $('#filtro_per_ini').val();
    filtro_per_fim = $('#filtro_per_fim').val();


    $('#tbody_parcelas').html('<tr><td colspan="10">Carregando Alertas</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

    carregar_totais();
    carregar_resultados();
}

function carregar_totais() {
    // tipo_operacao=$('#tipo_operacao').children('option:selected').val(); 	
    // <?php if($ehCliente){   ?>
    // 	filtrar = 1;
    // 	if (tipo_operacao == 'venda') {
    // 		filtro_vendedor = <?php echo $user_id;?>;
    // 		filtro_comprador = null;
    // 	} else {
    // 		filtro_vendedor = null;
    // 		filtro_comprador = <?php echo $user_id;?>;
    // 	}
    // 	<?php }  ?>

    // 	$('#linha_totais').html('');
    // 	$.getJSON('<?php echo $link."/repositories/parcelas/parcelas.ctrl.php?acao=listar_totais";?>',{
    // 		filtro_contrato_id:filtro_contrato_id,
    // 		filtro_tpcontrato:filtro_tpcontrato,
    // 		filtro_status:filtro_status,
    // 		filtro_ted_id:filtro_ted_id,
    // 		filtro_per_ini:filtro_per_ini,
    // 		filtro_per_fim:filtro_per_fim,
    // 		filtro_vendedor:filtro_vendedor,
    // 		filtro_comprador:filtro_comprador, 
    // 		filtro_status_ct:filtro_status_ct,
    // 		filtro_dia:filtro_dia,

    // 		filtrar:filtrar,
    // 		ajax: 'true'
    // 	}, function(j){
    // 		//alert(JSON.stringify(j));
    // 		totais = j.totais[0];
    // 		linha_total = 'Encontradas '+totais.total_parcelas+' parcelas';
    // 		linha_total += '<br>Valor Parcelas: R$ '+number_format(totais.vl_parcela,2,',','.');

    // 		<?php if(!$ehCliente){   ?>

    // 			//linha_total += '<br>Valor Juros/Correção: R$ '+number_format(totais.vl_juros,2,',','.');
    // 			linha_total += '<br>Valor Pagto: R$ '+number_format(totais.vl_pagto,2,',','.');
    // 			linha_total += '<br>Valor Honorários: R$ '+number_format(totais.vl_honorarios,2,',','.');
    // 			<?php }  ?>

    // 			$('#linha_totais').html(linha_total);
    // 			total_results = totais.total_parcelas;
    // 		});
}

function carregar_resultados() {
    //quantos já foram exibidos e descartar ids exibidos na cidade principal
    exibidos = document.getElementById("cont_exibidos").value;
    if (exibidos == 0) {
        nova_listagem = 1;
    } else {
        nova_listagem = 0;
    }

    document.getElementById("loading_resultados").style.display = 'block';
    libera_carregamento = 0;
    $.getJSON('<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=lista_alertas_usuario_ativo";?>&inicial=' +
        exibidos, {
            filtro_status: filtro_status,
            filtro_per_ini: filtro_per_ini,
            filtro_per_fim: filtro_per_fim,
            order: order,
            ordem: ordem,
            filtrar: filtrar,
            ajax: 'true'
        },
        function(j) {
            cont_novos = 0;
            novos = "";
            // console.log((j));
            for (var i = 0; i < j.length; i++) {
                exibidos++;
                cont_novos++;
                cor_fundo = 'white';
                cor_texto = 'black';

                if(j[i].remetente_admin == 'N' && j[i].remetente_user == 'N') {
                    cor_fundo = '#fff675';
                    // cor_texto = 'white';
                }

                //open tr
                novos += '<tr id="tr_' + j[i].id + '" style="color:'+ cor_texto +';background:'+ cor_fundo+';">';

                //td #
                novos += '<td>';
                novos += j[i].nome_remetente;
                novos += '</td>';



                novos += '<td class="hidden-xs hidden-sm">';
                novos += j[i].descricao;
                novos += '</td>';

                novos += '<td class="hidden-xs hidden-sm">';
				if (j[i].link != null) {
                	novos += '<a href="'+j[i].link+'">'+j[i].link+'</a>';
				}
                novos += '</td>';

				novos += '<td class="hidden-xs hidden-sm">';
				if (j[i].dt_prazo != 'NULL') {
                	novos += ConverteData(j[i].dt_prazo)
				}

                novos += '<td class="hidden-xs hidden-sm">';
				if (j[i].dt_concluido != 'NULL') {
                	novos += ConverteData(j[i].dt_concluido)
				}

				novos += '<td class="hidden-xs hidden-sm">';
				if (j[i].data_alerta != 'NULL') {
                	novos += ConverteData(j[i].data_alerta)
				}
                novos += '</td>';
                
                novos += '<td class="hidden-xs hidden-sm">';
				if (j[i].dt_concluido == null) {
                    novos += '<i  class="fa fa-exclamation-triangle red_light pointer" data-toggle="tooltip" data-placement="left" title="Marcar como Concluído" data-original-title="Marcar como Concluído"   onClick="concluir_alerta(' +
                        j[i].id + ');"   > </i>';
                }
                novos += '</td>';

                novos += '</tr>';
				// novos += ConverteData(j[i].dt_vencimento);

            }
            if (exibidos == 0) {
                novos = "<tr><td colspan='10'>Nenhum alerta</td></tr>";
            }
            //Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
            if (cont_novos == 30) {
                libera_carregamento = 1;
            }

            if (nova_listagem == 1) {
                $('#tbody_parcelas').html(novos);
            } else {
                $('#listagem_parcelas').append(novos);
            }
            document.getElementById("loading_resultados").style.display = 'none';
            document.getElementById("cont_exibidos").value = exibidos;
            document.getElementById("permite_carregar").value = libera_carregamento;
        });
}

function ordenar(campo) {
    order = campo;
    if (ordem == 'desc') {
        ordem = 'asc';
        icone = '<i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i>';
    } else {
        ordem = 'desc';
        icone = '<i class="fa fa-arrow-circle-down fl-rg ico_ordem" ></i>';
    }
    $('.ico_ordem').remove();
    $('#th_' + campo).append(icone);
    $('#cont_exibidos').val('0');
    carregar_resultados();

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

</body>

</html>