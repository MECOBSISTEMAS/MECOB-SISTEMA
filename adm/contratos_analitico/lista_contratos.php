<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active="contratos_analitico";
$layout_title = "MECOB - Contratos - Relatório Analítico";
$sub_menu_active="contratos_analitico";	
$tit_pagina = "Contratos - Analítico";	
$tit_lista = " Relatório analítico de contratos";	

if(!consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ 
	header("Location: ".$link."/401");
	exit;
}

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
                    <li class="hidden"><a href="#">Contratos - Relatório Analítico</a>&nbsp;&nbsp;<i
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
                                    <?php include($raiz."/adm/contratos_analitico/filtros_contratos.php");	?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-sm-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red"><i class="fa fa-file"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Vencidos</span>
                                            <span class="info-box-number" id="contratos_vencidos">0</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                                <div class="col col-sm-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-file"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">À vencer</span>
                                            <span class="info-box-number" id="contratos_a_vencer">0</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                                <div class="col col-sm-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-blue"><i class="fa fa-file"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Liquidados</span>
                                            <span class="info-box-number" id="contratos_liquidados">0</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                                <div class="col col-sm-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-grey"><i class="fa fa-file"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Suspensos</span>
                                            <span class="info-box-number" id="contratos_suspensos">0</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h4 class="mbs"></h4>
                                                    <div id="grafico_fluxo_mes_passado"
                                                        style="width:100%; height:300px"> </div>
                                                </div>
                                                <div class="col-md-4">
													<?php
													// $fluxo_este_mes = $dashboard->fluxo_este_mes($conexao_BD_1, $id_pessoa); 													
													?>
                                                    <span class="task-item"> Vencidos <small
                                                            class="pull-right text-muted" id="bar_vencidos">
                                                            
                                                        </small>
                                                        <div class="progress progress-sm">
                                                            <div role="progressbar" id="prog_vencidos" aria-valuenow="10" aria-valuemin="0"
                                                                aria-valuemax="100"
                                                                style="width: 0%; background-color:#DD4B39"
                                                                class="progress-bar "> </div>
                                                        </div>
                                                    </span>
                                                    <span class="task-item"> A vencer <small
                                                            class="pull-right text-muted" id="bar_a_vencer">
                                                        </small>
                                                        <div class="progress progress-sm">
                                                            <div role="progressbar" id="prog_a_vencer" aria-valuenow="10" aria-valuemin="0"
                                                                aria-valuemax="100"
                                                                style="width: 0%; background-color:#65BF73"
                                                                class="progress-bar "> </div>
                                                        </div>
                                                    </span>
                                                    <span class="task-item"> Liquidados <small
                                                            class="pull-right text-muted" id="bar_liquidados">
                                                        </small>
                                                        <div class="progress progress-sm">
                                                            <div role="progressbar" id="prog_liquidados" aria-valuenow="10" aria-valuemin="0"
                                                                aria-valuemax="100"
                                                                style="width: 0%; background-color:#198CD0"
                                                                class="progress-bar "> </div>
                                                        </div>
													</span>
													<span class="task-item"> Suspensos <small
                                                            class="pull-right text-muted" id="bar_suspensos">
                                                        </small>
                                                        <div class="progress progress-sm">
                                                            <div role="progressbar" id="prog_suspensos" aria-valuenow="10" aria-valuemin="0"
                                                                aria-valuemax="100"
                                                                style="width: 0%; background-color:#E6E6E6"
                                                                class="progress-bar "> </div>
                                                        </div>
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
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
<script src="<?php echo $link;?>/js/highcharts.js"></script>
<script src="<?php echo $link;?>/js/highcharts_exporting.js"></script>
<script src="<?php echo $link;?>/js/jquery.form.js"></script>
<script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js" />
</script>
<script src="<?php echo $link;?>/js/jquery.validate.js" />
</script>
<script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
<script src="<?php echo $link;?>/js/jquery.maskMoney.js" />
</script>
<!-- <script src="<?php echo $link;?>/js/ckeditor/ckeditor.js"></script> -->


<script>
function atualiza_chart(){
    data_inicial = $('#filtro_data').val();
    data_final = $('#filtro_data_fim').val();
    if (data_inicial != '') {
        if (data_final != '') {
            texto_title = 'De '+data_inicial+' até '+ data_final;
        } else {
            texto_title = 'A partir de '+data_inicial;
        }
    } else {
        if (data_final != '') {
            texto_title = 'Até '+ data_final;
        } else {
            texto_title = 'Período completo';
        }
    }
	$('[rel=tooltip]').tooltip();
    $('[data-toggle=popover]').popover();

    $('#grafico_fluxo_mes_passado').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: texto_title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },

        colors: ['#DD4B39', '#4CA65A', '#0073B7', '#CDCDCD'],

        series: [{
            name: 'Quantidade',
            colorByPoint: true,
            data: [{
                name: 'Vencidos',
                y: percent_vencidos,
                sliced: true,
                selected: true
            }, {
                name: 'A vencer',
                y: percent_a_vencer
            }, {
                name: 'Liquidados',
                y: percent_liquidados
            }, {
                name: 'Suspensos',
                y: percent_suspensos
            }]

        }]
    });
}
$(document).ready(function() {
	percent_vencidos = 0;
	percent_a_vencer = 0;
	percent_liquidados = 0;
	percent_suspensos = 0;
});

var filtro_contrato = "";
var filtro_status = "";
var filtro_pagto = "";
var filtro_dia = "";
var filtro_data = "";
var filtro_data_fim = "";
var filtro_vendedor = "";
var filtro_comprador = "";
var filtro_id = "";
var filtrar = 0;
var filtro_zerado = "";
var filtro_tipo = "";

var total_results = 0;

var order = "id";
var ordem = "desc";

var delay_busca;
$(function() {
    <?php 
	if(isset($ini_filtro) && $ini_filtro){
		?> filtrar_fields();
    <?php }
		else{
			?>carregar_resultados();
    <?php }?>
    $('[data-toggle="tooltip"]').tooltip();
    $("#inputDtContrato").mask("99/99/9999");
    $("#inputDtContrato").datepicker({
        dateFormat: 'dd/mm/yy'
    });
    $("#filtro_data").mask("99/99/9999");
    $("#filtro_data").datepicker({
        dateFormat: 'dd/mm/yy'
    });
    $("#filtro_data_fim").mask("99/99/9999");
    $("#filtro_data_fim").datepicker({
        dateFormat: 'dd/mm/yy'
    });


    $('#inputVlContrato').maskMoney();
    $('#inputVlEntrada').maskMoney();

});

<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

function limpa_filtros(contrato_id) {
    $('#filtro_status').val('');
    $('#filtro_pagto').val('');
    $('#filtro_data').val('');
    $('#filtro_data_fim').val('');
    $('#filtro_vendedor').val('');
    $('#filtro_comprador').val('');
    $('#filtro_id').val('');
    $('#filtro_zerado').val('');
    $('#filtro_tipo').val('');

    filtrar = 0;

    if (contrato_id) {
        $('#filtro_id').val(contrato_id);
    }

    filtrar_fields();
}
$('#filtro_contrato').val('');

function filtrar_fields() {
    filtro_contrato = $('#filtro_contrato').val();
    filtro_status = $('#filtro_status').val();
    filtro_pagto = $('#filtro_pagto').val();
    filtro_data = $('#filtro_data').val();
    filtro_data_fim = $('#filtro_data_fim').val();
    filtro_vendedor = $('#filtro_vendedor').val();
    filtro_comprador = $('#filtro_comprador').val();
    filtro_id = $('#filtro_id').val();
    filtro_zerado = $('#filtro_zerado').val();
    filtro_dia = $('#filtro_dia').val();
    filtro_tipo = $('#filtro_tipo').val();

    $('#tbody_contratos').html('<tr><td colspan="10">Carregando contratos</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

    carregar_resultados();
}

function filtra_contrato_relacionado(contrato_id) {
    limpa_filtros(contrato_id);
}

function carregar_resultados(open_parcelas) {
    //quantos já foram exibidos e descartar ids exibidos na cidade principal
    exibidos = '0';
    // if (exibidos == 0) {
    //     nova_listagem = 1;
    // } else {
    //     nova_listagem = 0;
    // }

    // document.getElementById("loading_resultados").style.display = 'block';
    libera_carregamento = 0;
    $.getJSON(
        '<?php echo $link."/repositories/contratos_analitico/contratos.ctrl.php?acao=lista_contratos";?>&inicial=' +
        exibidos, {
            filtro_contrato: filtro_contrato,
            filtro_data: filtro_data,
            filtro_data_fim: filtro_data_fim,
            filtro_pagto: filtro_pagto,
            filtro_status: filtro_status,
            filtro_vendedor: filtro_vendedor,
            filtro_comprador: filtro_comprador,
            filtro_id: filtro_id,
            filtro_zerado: filtro_zerado,
            filtro_tipo: filtro_tipo,
            order: order,
            ordem: ordem,
            filtrar: filtrar,
            filtro_dia: filtro_dia,
            ajax: 'true'
        },
        function(j) {
			vencidos = parseInt(j[0]['vencidos']);
			a_vencer = parseInt(j[0]['a_vencer']);
			liquidados = parseInt(j[0]['liquidados']);
			suspensos = parseInt(j[0]['suspensos']);
			
			total = vencidos + a_vencer + liquidados + suspensos;
			percent_vencidos = vencidos / total;
			percent_a_vencer = a_vencer / total;
			percent_liquidados = liquidados / total;
			percent_suspensos = suspensos / total;

			atualiza_chart();

			$('#contratos_vencidos, #bar_vencidos').html(vencidos);
			$('#contratos_a_vencer, #bar_a_vencer').html(a_vencer);
			$('#contratos_liquidados, #bar_liquidados').html(liquidados);
			$('#contratos_suspensos, #bar_suspensos').html(suspensos);

			$('#prog_vencidos').css('width',(percent_vencidos*100)+'%')
			$('#prog_a_vencer').css('width',(percent_a_vencer*100)+'%')
			$('#prog_liquidados').css('width',(percent_liquidados*100)+'%')
			$('#prog_suspensos').css('width',(percent_suspensos*100)+'%')
        });
}
</script>

</body>

</html>