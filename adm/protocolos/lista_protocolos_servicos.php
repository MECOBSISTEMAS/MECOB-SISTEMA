<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");

$menu_active = "protocolos";
$layout_title = "MECOB - Protocolos de Serviços";
$sub_menu_active = "servicos";
$tit_pagina = "Protocolos de Serviços";
$tit_lista = " Protocolos de Serviços";

$addcss = '<link rel="stylesheet" href="' . $link . '/css/smoothjquery/smoothness-jquery-ui.css">';

include($raiz . "/partial/html_ini.php");

include_once($raiz . "/inc/util.php");

?>
<head>
    <style>
    tr:not(.notThisOne):hover {
        background-color: lightgray !important;
        font-weight: bold;
        color: black !important;
    }
    td:not(.notThisOne) {
        font-weight: bold;
        /* color: black; */
    }
    td:first-child {
        background-color: white !important;
        color: black;
    }
    td:last-child {
        background-color: white !important;
    }
    td:nth-child(2){
        border-top-left-radius: 10px !important;
        /* border-bottom-left-radius: 25px !important; */
    }
    td:nth-child(8){
        border-top-right-radius: 10px !important;
    }
    table#listagem_protocolos, td {
        border: 0px solid black !important;
        border-collapse: collapse !important;
        /* color: black !important */
    }
    table#listagem_protocolos, tr {
        border: 1px solid white !important;
        border-collapse: collapse !important;
        /* color: black !important */
    }
    table#listagem_protocolos, th {
        border: 0px solid black !important;
        border-collapse: collapse !important;
        color: black !important
    }
    </style>
</head>
<div>
    <!--BEGIN BACK TO TOP-->
    <a id="totop" href="#"><i class="fa fa-angle-up"></i></a>
    <!--END BACK TO TOP-->
    <!--BEGIN TOPBAR-->
    <?php include($raiz . "/partial/header.php"); ?>
    <!--END TOPBAR-->
    <div id="wrapper">
        <!--BEGIN SIDEBAR MENU-->
        <?php include($raiz . "/partial/sidebar_adm.php"); ?>
        <!--END SIDEBAR MENU-->



        <div id="page-wrapper">
            <!--BEGIN TITLE & BREADCRUMB PAGE-->
            <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title">
                        <?php echo $tit_pagina; ?></div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link; ?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Protocolos de Serviços</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="active"><?php echo $tit_pagina; ?></li>
                </ol>
                <div class="clearfix">
                </div>
            </div>
            <!--END TITLE & BREADCRUMB PAGE-->
            <!--BEGIN CONTENT-->
            <div class="page-content">
                <div id="tab-general">
                    <div class="row mbl">
                        <div class="col-lg-13">
                            <div class="panel panel-bordo" style="background:#FFF;">
                                <div class="panel-heading"><?php echo $tit_lista; ?></div>
                                <div class="panel-body">
                                    <?php 
                                        if($_SESSION['perfil_id'] >= 1 || in_array($_SESSION['id'] , array('31'))) {
                                    ?>
                                            <h3><button type="button" class="btn btn-brown"
                                                    onClick="modal_cad_protocolos(0,'cadastro');">
                                                    Novo+</button>
                                            </h3>
                                    <?php } ?>

                                    <?php include($raiz . "/adm/protocolos/filtros_protocolos_servicos.php");    ?>

                                    <div id="linha_totais"></div>

                                    <div class="row">
                                        <span onMouseOver='$( "#bt_cores" ).click();'
                                            onMouseOut='$( "#bt_cores" ).click();' id="bt_cores"
                                            class="fa fa-th-list fs-24 pull-right mg-lf-5 mg-tp-12 blue"
                                            data-placement="left" data-toggle="popover" title="Legenda dos Setores"
                                            data-html="true" data-content='
                                            <div class="leg_ico_geral3">
                                                <div class="leg_ico_unico">
                                                    <span class="fa fa-square" style="color: purple"></span> - Confirmação<br>
                                                    <span class="fa fa-square" style="color: olive"></span> - Boletos<br>
                                                    <span class="fa fa-square" style="color: fuchsia"></span> - Jurídico<br>
                                                    <span class="fa fa-square" style="color: dark"></span> - Cancelado<br>
                                                </div>
                                            </div>'>
                                        </span>
                                        &nbsp;
                                        &nbsp;
                                        <span onMouseOver='$( "#bt_legenda" ).click();'
                                            onMouseOut='$( "#bt_legenda" ).click();' id="bt_legenda"
                                            class="fa fa-th-list fs-24 pull-right mg-lf-5 mg-tp-12 gray_system"
                                            data-placement="left" data-toggle="popover" title="Legenda da Ação"
                                            data-html="true" data-content='
                                            <div class="leg_ico_geral3">
                                                <div class="leg_ico_unico">
                                                    <span class="fa fa-print"></span> - Impressão do Protocolo<br>
                                                    <span class="fa fa-share"></span> - Troca de Setor<br>
                                                    <span class="fa fa-comments"></span> - Ocorrências<br>
                                                    <span class="fa fa-check-square-o"></span> - Finaliza o Protocolo<br>
                                                    <span class="fa fa-close"></span> - Remove o Protocolo <br>
                                                    <span class="fa fa-trash-o"></span> - Remove o Protocolo <br>
                                                </div>
                                            </div>'>
                                        </span>
                                    </div>


                                    <div id="listagem" class="box-body no-padding">
                                        <table id="listagem_protocolos" class="table table-bordered" >
                                            <thead>
                                                <tr class="notThisOne">
                                                    <!-- <th id="th_id" class="hidden-xs hidden-sm pointer"
                                                        onclick="ordenar('id');">id 
                                                        <i class="fa fa-arrow-circle-down fl-rg ico_ordem"></i></th> -->

                                                    <th id="th_id"           class="pointer" onclick="ordenar('id');">ID</th>
                                                    <th id="th_nome"         class="pointer" onclick="ordenar('nome');">Nome</th>
                                                    <th id="th_tipo"         class="pointer" onclick="ordenar('tipo');">Tipo</th>
                                                    <th id="th_enviado"      class="pointer" onclick="ordenar('enviado');">Enviado</th>
                                                    <th id="th_recebido"     class="pointer" onclick="ordenar('recebido');">Recebido</th>
                                                    <th id="th_digitalizado" class="pointer" onclick="ordenar('digitalizado');">Digitalizado</th>
                                                    <th id="th_fisico"       class="pointer" onclick="ordenar('fisico');">Físico</th>
                                                    <th id="th_oservacao"    class="pointer" >Observação</th>

                                                    <th> Ação </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_protocolos">
                                                <tr>
                                                    <td id="td_carregando" colspan="10">Carregando protocolos</td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <div id="mais_resultados"></div>
                                        <div id="loading_resultados"
                                            style="display:none; text-align:center; color:#667;">
                                            <h4> <img src="<?php echo $link . "/imagens/loading_circles.gif"; ?>"
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
            <?php include($raiz . "/partial/footer.php"); ?>
            <!--END FOOTER-->
        </div>
        <!--END PAGE WRAPPER-->
    </div>
</div>

<!-- modal cadastro de protocolos-->
<div class="modal fade" id="md_protocolos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-80p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_protocolo_edit"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_contratos_bd">
                <div class="panel panel-bordo">
                    <div id="cadastro_header" class="panel-heading">
                        Novo <?php echo $tit_pagina; ?> </div>
                    <div class="panel-body pan">


					<form id="form_protocolos" action="javascript:salvarFormulario()" autocomplete="off">
                        <div class="form-body pal pd-tp-0">

                            <div class="row">
                                <div class="col-md-8 bd-lf">
                                    <h3>Cliente</h3>

                                    <div id="row_status" class="row">
                                        <div id="quadro_vendedores" class="col-md-4 hidden">
                                            <div class="row">
                                                <div class="col-xs-4 col-sm-3 ">
                                                    <img id="img_vendedores" src="" class="img-responsive img-circle  wd-100p" />
                                                </div>
                                                <div id="info_vendedores" class="col-xs-8 col-sm-9">
                                                </div>
                                            </div>
                                            <br />
                                        </div>
                                        <div id="select_vendedores" class="col-md-12">
                                            <div class="form-group input-icon right">

                                                <div class="placeholder">Nome:</div>
                                                <input id="inputVendedor" name="vendedor" type="text" placeholder="Vendedor"
                                                    class="form-control  with-placeholder" autocomplete="off"
                                                    onkeyup="busca_pessoa('vendedores');" />
                                                <input id="inputVendedorId" name="vendedor_id" type="hidden" />
                                                <input id="inputVendedorNome" name="vendedor_nome" type="hidden" />
                                                <div id="autocp_vendedores" class="hidden autocp_div">
                                                    <div id="div_loading_autocp" class="row loading_something hidden ">
                                                        <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <h3> Tipo</h3>
                                <div class="col-md-4">
                                    <select name="tipo" id="tipo" class="form-control"  >
                                    <option value=""> Tipo </option>
                                    <option value="Ambos"> Ambos</option>
                                    <option value="Controle Financeiro"> Controle Financeiro</option>
                                    <option value="Recuperação de Crédito"> Recuperação de Crédito</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Enviado</div>
                                        <input id="inputEnviado" name="enviado" type="text" placeholder="Enviado"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Recebido</div>
                                        <input id="inputRecebido" name="recebido" type="text" placeholder="Enviado"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Digitalizado</div>
                                        <input id="inputDigitalizado" name="digitalizado" type="text" placeholder="Enviado"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Fisico</div>
                                        <input id="inputFisico" name="fisico" type="text" placeholder="Enviado"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>



                            </div>


                            <div class="form-group">
                                <h3>Observações:</h3>
                                <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Máximo de 500 caracteres..."></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button id="btn-save-protocolo" name="btn-save-protocolo" type="button" class="btn btn-brown control_edit_protocolo_div"
                                    onClick="$('#form_protocolos').submit()">Salvar</button>
                            </div>

                        </div>
                        <input id="inputUsuario" name="usuario" type="hidden" />
                        <input id="inputDt_registro" name="dt_registro" type="hidden" />
                        <input id="inputDt_atualizacao" name="dt_atualizacao" type="hidden" />
                        <input id="inputId" type="hidden" name="id" placeholder="Id" class="form-control" />

					    <button type="submit" class="hidden"></button>


					</form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal finalizar protocolos-->
<div class="modal fade" id="md_finalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-50p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_protocolo_edit"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_contratos_bd">
                <div class="panel panel-bordo">
                    <div id="finalizar_header" class="panel-heading">
                        Finalizar o Protocolo </div>
                    <div class="panel-body pan">


					<form id="form_finalizar" action="javascript:salvarFinalizar()">
                        <div class="form-body pal pd-tp-0">

                            <h3 class="mg-tp-0">Informe o ID do contrato para encerrar o protocolo</h3>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Contrado ID #</div>
                                        <input id="inputFinalizarContratoID" name="contrato_id" type="text" placeholder=""
                                            class="form-control  with-placeholder control_edit_contrato_input" />
                                    </div>
                                </div>

                            </div>

                        </div>
                        <input id="inputFinalizarUsuario" name="finalizar_usuario" type="hidden" />
                        <input id="inputFinalizarProtocoloID" name="finalizar_protocolo_id" type="hidden" />

                        <button type="submit" class="hidden"></button>
					</form>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="btn-save-finalizar" type="button" class="btn btn-brown control_edit_protocolo_div"
                    onClick="$('#form_finalizar').submit()">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- fim md_finalizar -->

<!-- fim cadastro de pessoas-->
<?php include $raiz . "/js/corejs.php"; ?>
<script src="<?php echo $link; ?>/js/jquery.form.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
<script src="<?php echo $link; ?>/js/jquery.validate.js"/></script>
<script src="<?php echo $link; ?>/js/jquery.inputmask.bundle.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskMoney.js"/></script>
<script src="<?php echo $link; ?>/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo $link; ?>/js/moment.js"></script>

<script>
    // Ativa o menu correto
    $('#a_animate_sidebar_protocolos').click();

$(document).ready(function() {
    $('[rel=tooltip]').tooltip();
    $('[data-toggle=popover]').popover();
});


var filtro_vendedor     = "";
var filtro_tipo         = "";

var filtrar = 0;
var order = "nome";
var ordem = "asc";

var delay_busca;

$(function() {

    <?php 
        if (isset($ini_filtro) && $ini_filtro) {
            ?> filtrar_fields();
    <?php 
        } else {
            ?> carregar_resultados();
    <?php 
    } ?>


    $("#inputPrazo").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        minDate: 0,
		// maxDate: '0',
    });

    $("#inputEnviado").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#inputRecebido").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#inputDigitalizado").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#inputFisico").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });


    $("#filtro_data").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#filtro_prazo").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });


});

function modal_cad_protocolos(id, tipo, dados=null) {

    // let data = moment().format('YYYY-MM-DD HH:mm:ss');
    // let data = '<?php echo date('Y-m-d H:i:s'); ?>';
    var data = Date.now();
    var usuario = <?php echo $_SESSION['id']; ?>;
    var proto_title = 'Novo Protocolo de Serviço';

    if (tipo == 'cadastro') {


        $("input#inputUsuario").val(usuario);
        $("input#inputDt_registro").val(moment(data).format('YYYY-MM-DD HH:mm:ss'));
        $("input#inputDt_atualizacao").val(moment(data).format('YYYY-MM-DD HH:mm:ss'));

        $("input#inputId").val('');

        $("input#inputVendedor").val('');
        $("input#inputVendedorId").val('');
        $("input#inputVendedorNome").val('');

        $("select#tipo").val('');

        $("input#inputEnviado").val('');
        $("input#inputRecebido").val('');
        $("input#inputDigitalizado").val('');
        $("input#inputFisico").val('');

        $("textarea#observacao").val('');

        
        // Limpa os dados do vendedor e comprador
        limpa_pessoa(0);

    } else if (tipo == 'editar') {
        proto_title = 'Protocolo Edição';
        // console.log('Dados: ' + JSON.stringify(dados));

        $("input#inputId").val(id);
        $("input#inputUsuario").val(usuario);

        $("input#inputDt_registro").val(moment(dados.dt_registro).format('YYYY-MM-DD HH:mm:ss'));
        $("input#inputDt_atualizacao").val(moment(data).format('YYYY-MM-DD HH:mm:ss'));

        // Limpa a busca do vendedor
        $('#inputVendedorId').val('');
        $('#inputVendedorNome').val('');
        $('#quadro_vendedores').addClass('hidden');
        $('#select_vendedores').removeClass('hidden');

        $("input#inputVendedor").val(dados.nome);
        $("input#inputVendedorNome").val(dados.nome);

        $("select#tipo").val(dados.tipo);

        if(dados.enviado == '0000-00-00') {
            $("input#inputEnviado").val('');
        } else {
            $("input#inputEnviado").val(moment(dados.enviado).format('DD/MM/YYYY'));
        }

        if(dados.recebido == '0000-00-00') {
            $("input#inputRecebido").val('');
        } else {
            $("input#inputRecebido").val(moment(dados.recebido).format('DD/MM/YYYY'));
        }

        if(dados.digitalizado == '0000-00-00') {
            $("input#inputDigitalizado").val('');
        } else {
            $("input#inputDigitalizado").val(moment(dados.digitalizado).format('DD/MM/YYYY'));
        }

        if(dados.fisico == '0000-00-00') {
            $("input#inputFisico").val('');
        } else {
            $("input#inputFisico").val(moment(dados.fisico).format('DD/MM/YYYY'));
        }

        $("textarea#observacao").val(dados.observacao);

    } else if (tipo == 'copiar') {

        proto_title = 'Protocolo Cópia';

        $("input#inputUsuario").val(usuario);

        // Retorna o proximo dia útil daqui a 5 dias, ver inc/util.php
        dias_uteis = '<?php echo protocolos_feriados_range(); ?>';

        $("input#inputUsuario").val(usuario);

        // Renova os dados abaixo
        $("input#inputId").val('');
        $("input#inputProtocolo").val(moment(data).format('YYMMDDHHmmss'));
        $("input#inputData").val(moment(data).format('DD/MM/YYYY HH:mm:ss'));
        $("input#inputPrazo").val(moment(dias_uteis).format('DD/MM/YYYY'));
        $("input#inputSetor").val('Confirmação');

        // Usa os mesmos dados do anterior
        $("input#inputVendedor").val(dados.vendedor);
        $("input#inputVendedorId").val(dados.vendedor_id);
        $("input#inputVendedorNome").val(dados.vendedor);
        $("input#inputComprador").val(dados.comprador);
        $("input#inputCompradorId").val(dados.comprador_id);
        $("input#inputCompradorNome").val(dados.comprador);
        $("input#inputEvento").val(dados.evento);
        $("input#inputEventoId").val(dados.evento_id);
        $("input#inputEventoNome").val(dados.evento);
        $("input#inputProduto").val(dados.produto);
        $("input#inputValor").val(dados.valor);
        $("input#inputDt_parcela").val(moment(dados.dt_parcela).format('DD/MM/YYYY'));
        $("input#inputNr_parcela").val(dados.nr_parcela);
        $("#observacao").val(dados.observacao);
    }



    $("#cadastro_header").text(proto_title);

    $("#btn-save-protocolo").attr("disabled", false);
    $('#md_protocolos').modal('show');
}

function filtrar_fields() {

    filtro_vendedor = $('#filtro_vendedor').val();
    filtro_tipo     = $('select#filtro_tipo').val();


    $('#tbody_protocolos').html('<tr><td colspan="10">Carregando protocolos</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

    carregar_resultados('Filtrar');
}

function limpa_filtros() {
    $('#filtro_vendedor').val('');
    $('#filtro_tipo').val('');

    // $('#form_filtros_protocolos').parents('.input-group').removeClass('red');

    filtrar = 0;

    filtrar_fields();
}

function carregar_resultados(dados=null) {
    // Carrega os totais antes dos protocolos
    // carregar_totais();

    //quantos já foram exibidos e descartar ids exibidos na cidade principal
    exibidos = document.getElementById("cont_exibidos").value;

    if (dados != null) {
        exibidos = 0;
    }

    if (exibidos == 0) {
        nova_listagem = 1;
    } else {
        nova_listagem = 0;
    }
    
    document.getElementById("loading_resultados").style.display = 'block';
    libera_carregamento = 0;

    // console.log("Filtrar " + JSON.stringify(filtrar));
    // console.log("Protocolo_ID " + JSON.stringify(filtro_vendedor));

    $.getJSON('<?php echo $link . "/repositories/protocolos/protocolos_servicos.ctrl.php?acao=lista_protocolos_servicos"; ?>&inicial=' +
        exibidos, {
            order: order,
            ordem: ordem,
            filtrar: filtrar,
            filtro_vendedor: filtro_vendedor,
            filtro_tipo: filtro_tipo,
            
            filtro_pagina: 'protocolos',
            ajax: 'true'
        },
        function(j) {
            // console.log(JSON.stringify(filtro_protocolo_id));
            
            cont_novos = 0;
            novos = "";
            data_atual = "<?php echo date('Y-m-d'); ?>"

            for (var i = 0; i < j.length; i++) {
                exibidos++;
                cont_novos++;

                if(j[i].observacao && j[i].observacao.length) {
                    observacao = j[i].observacao;
                } else {
                    observacao = ' ';
                }
                //open tr
                // protocolos_aux = JSON.stringify(j[i]);

                // Fundo padrão
                fundo_tr = '#ffffff';

                // Monta campo Status da tabela com a bolinhas
                // fundo_tr_status = '#78d5fa';


                fundo_tr_status = 'bg-blue';
                novos += '<tr class="' + fundo_tr_status + '" id="tr_' + j[i].id + '">';

                // ID
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].id || "" );
                novos += '</td>';

                // Nome
                novos += '<td class="nowrap hidden-xs hidden-sm">';
                novos += ( j[i].nome || "" );
                novos += '</td>';

                // Tipo
                novos += '<td class="nowrap hidden-xs hidden-sm">';
                novos += ( j[i].tipo || "" );
                novos += '</td>';

                // enviado
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( ConverteData(j[i].enviado) || "" );
                novos += '</td>';

                // recebido
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( ConverteData(j[i].recebido) || "" );
                novos += '</td>';

                // digitalizado
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( ConverteData(j[i].digitalizado) || "" );
                novos += '</td>';

                // fisico
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( ConverteData(j[i].fisico) || "" );
                novos += '</td>';

                // observacao
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( observacao.length > 70 ? observacao.substring(0,70) + ' ...' : observacao );
                novos += '</td>';


                //td acao
                novos += "<td class='nowrap'>";

                    // Editar
                    novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' "
                        + " onClick='modal_cad_protocolos(" +
                        j[i].id + ", \"editar\", " + JSON.stringify(j[i])
                        + " )'; > <i class='fa fa-pencil-square-o blue fs-18' > </i></span> </a>";

                    // Cancelamento
                    novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Remover' data-original-title='Remover' "
                        + " onClick='remover(" 
                        + j[i].id + ", \"" 
                        + j[i].nome 
                        + "\" )'; > <i class='fa fa-close red fs-18' > </i></span> </a>";

                    // Impressão  
                    novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Impressão' data-original-title='Impressão' onClick='imprimir(" +
                        j[i].id 
                        + " )'; > <i class='fa fa-print fs-18' > </i></span> </a>";

                novos += "</td>";

                novos += '</tr>';

                // console.log('teste ' + dados + " exibidos " + j[i].id);

            }
            if (exibidos == 0) {
                novos = "<tr><td colspan='10'>Nenhum protocolo foi encontrado</td></tr>";
            }
            //Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
            if (cont_novos == 30) {
                libera_carregamento = 1;
            }

            if (nova_listagem == 1) {
                $('#tbody_protocolos').html(novos);
            } else {
                $('#listagem_protocolos').append(novos);
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


/*  AJAX PESSOAS = COMPRADOR E VENDEDOR  */

function busca_pessoa(tipo_pessoa) {

    if (tipo_pessoa == 'vendedores') {
        palavra = $('#inputVendedor').val();
        $('#inputVendedorId').val('NULL');
        $('#inputVendedorNome').val('');
    } else {
        palavra = $('#inputComprador').val();
        $('#inputCompradorId').val('NULL');
        $('#inputCompradorNome').val('');
    }

    tam_palavra = palavra.length;
    if (tam_palavra >= 3) {
        $('#autocp_' + tipo_pessoa).removeClass('hidden');
        $('.loading_something').removeClass('hidden');
        if (delay_busca) {
            clearTimeout(delay_busca);
        }
        delay_busca = setTimeout(function() {
            $.get("<?php echo $link."/adm/pessoas/lista_pessoas_autocomplete.ajax.php";?>", {
                palavra: palavra,
                tipo_pessoa: tipo_pessoa
            }, function(result) {
                if (result == '0') {
                    //limpa resultados div_loading
                    $('.autocp_div').html(div_loading_autocp);
                    $('.autocp_div').addClass('hidden');
                    click_condominos_control = 0;
                } else {
                    //exibe resultados
                    $('#autocp_' + tipo_pessoa).html(result);
                    $('#autocp_' + tipo_pessoa).removeClass('hidden');
                    click_condominos_control = 1;
                }
            });
        }, 500);
    } else {
        //limpa resultados
        $('.autocp_div').html(div_loading_autocp);
        $('.autocp_div').addClass('hidden');
        click_condominos_control = 0;
    }

}

function escolhe_autocomplete_pessoa(pessoa, tipo_pessoa) {
    // alert(JSON.stringify(pessoa));
    if (tipo_pessoa == 'vendedores') {
        $('#inputVendedorId').val(pessoa.id);
        $('#inputVendedorNome').val(pessoa.nome);
    } else {
        $('#inputCompradorId').val(pessoa.id);
        $('#inputCompradorNome').val(pessoa.nome);
    }

    btn_limpa_pessoa = "";
    btn_limpa_pessoa = '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_pessoa(&#39;' +
        tipo_pessoa + '&#39;);"></span>';
    pessoainfo = btn_limpa_pessoa + pessoa.nome + "<br>" + pessoa.email;
    $("#info_" + tipo_pessoa).html(pessoainfo);
    $("#img_" + tipo_pessoa).attr("src", "<?php echo getenv('CAMINHO_SITE')."/imagens/fotos/nail/";?>" + pessoa.foto);


    $('#autocp_' + tipo_pessoa).addClass('hidden');
    $('#select_' + tipo_pessoa).addClass('hidden');
    $('#quadro_' + tipo_pessoa).removeClass('hidden');
}

function limpa_pessoa(tipo_pessoa) {
    if (tipo_pessoa == 'compradores') {
        $('#inputCompradorId').val('');
        $('#quadro_compradores').addClass('hidden');
        $('#select_compradores').removeClass('hidden');
    } else if (tipo_pessoa == 'vendedores') {
        $('#inputVendedorId').val('');
        $('#inputVendedorNome').val('');
        $('#quadro_vendedores').addClass('hidden');
        $('#select_vendedores').removeClass('hidden');
    } else {
        $('#inputCompradorId').val('');
        $('#inputVendedorId').val('');
        $('#inputVendedorNome').val('');
        $('#quadro_compradores').addClass('hidden');
        $('#select_compradores').removeClass('hidden');
        $('#quadro_vendedores').addClass('hidden');
        $('#select_vendedores').removeClass('hidden');
    }

}





$("#form_protocolos").validate({
    rules: {
        vendedor: {
            required: true
        },
        tipo: {
            required: true
        },
    },
    messages: {
        vendedor: "* Inform o nome do cliente!",
        tipo: "* Selecione um tipo!",
        observacao: {
            required: " * O campo observação é obrigatório!",
            minlength: jQuery.format(" * Mínimo {0} characters!"),
            maxlength: jQuery.format(" * Máximo {0} characters!"),
            rangelength: jQuery.format("Entre com pelo menos {0} characters e no maximo com {1} caracteres!"),
        }

    },
    errorClass: "validate-msg",
    errorElement: "div",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.form-group').addClass('red');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass('red');
    }
});

function salvarFormulario() {
    $("#btn-save-protocolo").attr("disabled", true);

    id = $("input#inputId").val();
    if (id.length == 0) {
        acao = 'inserir';
    } else {
        acao = 'atualizar';
    }
    // protocolos = $('#form_protocolos').serializeArray();
    
    // alert(JSON.stringify(protocolos));
    usuario        = $("input#inputUsuario").val();

    nome           = $("input#inputVendedor").val();
    vendedor_id    = $("input#inputVendedorId").val();
    vendedor_nome  = $("input#inputVendedorNome").val();

    tipo           = $( "select#tipo option:selected" ).val();
    enviado        = moment($("input#inputEnviado").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    recebido       = moment($("input#inputRecebido").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    digitalizado   = moment($("input#inputDigitalizado").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    fisico         = moment($("input#inputFisico").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    
    observacao     = $("#observacao").val();

    dt_registro  = moment($("input#inputDt_registro").val()).format('YYYY-MM-DD HH:mm:ss');
    dt_atualizacao = moment($("input#inputDt_atualizacao").val()).format('YYYY-MM-DD HH:mm:ss');


    if(acao == 'atualizar' && observacao == '') {
        observacao = ' ';
    }

    // Verifica se deve usar o nome do vendedor do cadastrado
    if(vendedor_id !== undefined && vendedor_id !== null && vendedor_id !== '' && vendedor_id != 'NULL') {
        nome = vendedor_nome;
    }


    protocolos_servicos = [
        { name: 'id', value: id },
        { name: 'nome', value: nome },
        { name: 'tipo', value: tipo },

        { name: 'enviado', value: enviado },
        { name: 'recebido', value: recebido },
        { name: 'digitalizado', value: digitalizado },
        { name: 'fisico', value: fisico },

        { name: 'observacao', value: observacao },

        { name: 'dt_registro', value: dt_registro },
        { name: 'dt_atualizacao', value: dt_atualizacao },
        { name: 'pessoa_id', value: usuario },
    ];

    // alert(JSON.stringify(protocolos))

    $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos_servicos.ctrl.php?acao="; ?>" + acao, {
        protocolos_servicos: protocolos_servicos
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

            document.getElementById("cont_exibidos").value = 0;
            order = "nome";
            ordem = "asc";
            if (acao == 'inserir') {
                carregar_resultados(result.status);
            } else {
                carregar_resultados();
            }

            $('#md_protocolos').modal('hide');

            if (result.status == '9') {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            } else {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            }
        } else {
            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
        }
    });
    // $("#btn-save-protocolo").attr("disabled", false);
}

function imprimir(protocolo_id=null) {
    // a opção name faz abrir a impressão sempre na mesma página.
    // window.open("<?php echo $link . "/repositories/protocolos/protocolos_servicos.rpt.php?protocolo_id="; ?>"+protocolo_id, 'name'); 
    window.open("<?php echo $link . "/repositories/protocolos/protocolos_servicos.rpt.php?protocolo_id="; ?>"+protocolo_id, '_blank'); 
}


function remover(id=null, nome=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    acao = 'remover';

    jConfirm(   'Cliente: ' + nome,
                'Deseja remover o protocolo: ' + id + '?',
            function(r) {
                if (r) { 
                    $.getJSON("<?php echo $link."/repositories/protocolos/protocolos_servicos.ctrl.php?acao=";?>" + acao, 
						{   id: id,
                            usuario: usuario
						}, function(result){
                        if( result.status == 1 ){
                            carregar_resultados(result.status);
                        }
                        else{
                            jAlert(result.status + ' | '+result.msg,'Alerta','alert');
                        }
                    });
                } else {
                    jAlert('Nada foi alterado!', 'Ação cancelada', 'alert');
                }
            });
}


$("#form_setor").validate({
    rules: {
        inputTrocaSetor: {
            required: true,
        },
    },
    messages: {
        inputTrocaSetor: "* Selecione o novo setor!",
    },
    errorClass: "validate-msg",
    errorElement: "div",
    highlight: function(element, errorClass, validClass) {
        // console.log('Element ' + element);
        $(element).parents('.form-group').addClass('red');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass('red');
    }
});


function gerar_planilha_protocolos() {
    var total_results = 1;
    if (total_results > 5000) {
        jAlert('Você está tentando gerar uma planilha com mais ' + total_results +
            ' registros.<br>Para não prejudicar seu processo limite a sua consulta a até 5000 registros.', 'Oops');
    } else {
        direct = '<?php echo $link . "/adm/protocolos/gera_planilha_protocolos_servicos.php"; ?>?order=' + order + '&ordem=' +
            ordem;
        $('#form_filtros_protocolos').attr('action', direct);
        $('#form_filtros_protocolos').attr('target', '_blank');
        $('#form_filtros_protocolos').submit();
        $('#form_filtros_protocolos').attr('action', 'javascript:filtrar_fields();');
        $('#form_filtros_protocolos').attr('target', '_top');
    }
}

function carregar_totais() {

    $('#linha_totais').html('');
    $.getJSON('<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao=lista_totais"; ?>', {
        filtrar: filtrar,
        filtro_vendedor: filtro_vendedor,
        filtro_tipo: filtro_tipo,

        
        filtro_pagina: 'protocolos',
        ajax: 'true'

    }, function(dados) {
        // console.log('Dados: ' + JSON.stringify(j));
        var texto = 'Total ' + dados['qtd'] + ' protocolo(s) no valor R$ ' + number_format(dados['valor'], 2);
        texto += (dados['pendente'] > 0 && dados['pendente'] < dados['qtd'] ) ? ' - Pendentes ' +  dados['pendente'] : '' ;
        texto += (dados['finalizado'] > 0 && dados['finalizado'] < dados['qtd'] ) ? ' - Finalizados ' +  dados['finalizado'] : '' ;
        texto += (dados['cancelado'] > 0 && dados['cancelado'] < dados['qtd']) ? ' - Cancelados ' +  dados['cancelado'] : '' ;
        texto += (dados['atrasado'] > 0 ) ? '<br>Vencido(s) ' +  dados['atrasado'] : '' ;

        $('#linha_totais').html(texto);
        total_results = dados['qtd'];
    });


}

$("#form_filtros_protocolos").validate({
    rules: {
        // filtro_protocolo_id: {
        //     number: true
        // },
    },
    messages: {
        // filtro_protocolo_id: "* Informe apena números",
    },
    errorClass: "validate-msg",
    errorElement: "div",
    highlight: function(element, errorClass, validClass) {
        $(element).parents('.input-group').addClass('red');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents('.input-group').removeClass('red');
    }
});

function fecharBuscaPessoa(){
    $('.autocp_div').addClass('hidden');
}

$( "select#tipo" ).focusin(function() {
    fecharBuscaPessoa();
});

</script>

</body>

</html>
