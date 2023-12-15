<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");

$menu_active = "protocolos";
$layout_title = "MECOB - Protocolos";
$sub_menu_active = "contratos";
$tit_pagina = "Protocolos";
$tit_lista = " Protocolos de contratos";

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
    }
    td:last-child {
        background-color: white !important;
    }
    td:nth-child(2){
        border-top-left-radius: 10px !important;
        /* border-bottom-left-radius: 25px !important; */
    }
    td:nth-child(12){
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
                    <li class="hidden"><a href="#">Protocolos</a>&nbsp;&nbsp;<i
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
                                        // if (consultaPermissao($ck_mksist_permissao, "cad_contratos", "adicionar")) { 
                                        if($_SESSION['perfil_id'] >= 1 || $_SESSION['id'] == '4666') {
                                    ?>
                                            <h3><button type="button" class="btn btn-brown"
                                                    onClick="modal_cad_protocolos(0,'cadastro');">
                                                    Novo Protocolo</button>
                                            </h3>
                                    <?php } ?>

                                    <?php include($raiz . "/adm/protocolos/filtros_protocolos.php");    ?>

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
                                                    <span class="fa fa-square" style="color: orange"></span> - Contratos<br>
                                                    <span class="fa fa-square" style="color: olive"></span> - Boletos<br>
                                                    <span class="fa fa-square" style="color: fuchsia"></span> - Jurídico<br>
                                                    <span class="fa fa-square" style="color: lightBlue"></span> - Embriões<br>
                                                    <span class="fa fa-square" style="color: yellow"></span> - Advogados<br>
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
                                                    <span class="fa fa-print"></span> - Imprssão do Protocolo<br>
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

                                                    <th id="th_status" class="pointer" onclick="ordenar('status');">Status</th>
                                                    <th id="th_protocolo" class="pointer" onclick="ordenar('protocolo');">Protocolo</th>
                                                    <th id="th_dt_registro" class="pointer" onclick="ordenar('dt_registro');">Data</th>
                                                    <th id="th_prazo" class="pointer" onclick="ordenar('prazo');">Prazo</th>
                                                    <th id="th_vendedor" class="pointer" onclick="ordenar('vendedor');">Vendedor</th>
                                                    <th id="th_comprador" class="pointer" onclick="ordenar('comprador');">Comprador</th>
                                                    <th id="th_evento" class="pointer" onclick="ordenar('evento');">Evento</th>
                                                    <th id="th_produto" class="pointer" onclick="ordenar('produto');">Produto</th>
                                                    <th id="th_valor" class="pointer" onclick="ordenar('valor');">Valor</th>
                                                    <th id="th_situacao" class="pointer" onclick="ordenar('situacao');">Setor<br>Situação</th>
                                                    <th id="th_dt_contrato" class="pointer" onclick="ordenar('dt_contrato');">Contrato<br>Digitalizado</th>
                                                    <th id="th_contrato_id" class="pointer" onclick="ordenar('contrato_id');">ID<br>Contrato</th>

                                                    <th> Ação </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_protocolos">
                                                <tr>
                                                    <td id="td_carregando" colspan="10">Carregando protocolo</td>
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
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_protocolo_edit"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_contratos_bd">
                <div class="panel panel-bordo">
                    <div id="cadastro_header" class="panel-heading">
                        Novo Protocolo <?php // echo $tit_pagina; ?></div>
                    <div class="panel-body pan">


					<form id="form_protocolos" action="javascript:salvarFormulario()" autocomplete="off">
                        <div class="form-body pal pd-tp-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Protocolo #</div>
                                        <input id="inputProtocolo" name="protocolo" type="text" placeholder="Protocolo" autocomplete="on"
                                            class="form-control  with-placeholder control_edit_contrato_input" readonly />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Data do Protocolo</div>
                                        <input id="inputData" name="data" type="text" placeholder="Data de registro"
                                            class="form-control  with-placeholder control_edit_contrato_input" readonly />
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Status</div>
                                        <input id="inputStatus" name="status" type="text" placeholder="Status inicial"
                                            class="form-control  with-placeholder control_edit_protocolo_input" readonly />
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Setor</div>
                                        <input id="inputSetor" name="setor" type="text" placeholder="Setor inicial"
                                            class="form-control  with-placeholder control_edit_protocolo_input" readonly />
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Produto</div>
                                        <input id="inputProduto" name="produto" type="text" placeholder="Informe o produto"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Valor</div>
                                        <input id="inputValor" name="valor" type="text" placeholder="Informe o valor do contrato"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Data 1ª parcela</div>
                                        <input id="inputDt_parcela" name="dt_parcela" type="text" placeholder="Informe a data da 1 parcela"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Número da 1ª parcela</div>
                                        <input id="inputNr_parcela" name="nr_parcela" type="text" placeholder="Informe o número da 1 parcela"
                                            class="form-control  with-placeholder control_edit_contrato_input"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Prazo</div>
                                        <input id="inputPrazo" name="prazo" type="text" placeholder="Informe o prazo final"
                                            class="form-control  with-placeholder control_edit_contrato_input" readonly />
                                    </div>
                                </div>

                            </div>

                            <!-- Contratos / Venda direta -->
                            <div class="row hidden" id="div_venda_direta">
    
                            <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Data recebimento do Contrato</div>
                                        <input id="inputDt_contrato" name="dt_contrato" type="text" placeholder=""
                                            class="form-control  with-placeholder control_edit_contrato_input" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group input-icon right">
                                        <div class="placeholder">Data da digitalização do Contrato</div>
                                        <input id="inputDt_digitalizado" name="dt_digitalizado" type="text" placeholder=""
                                            class="form-control  with-placeholder control_edit_contrato_input" />
                                    </div>
                                </div>

                            </div>

					        <div class="row">
					            <div class="col-md-4">
					                <h3>Leilão</h3>

					                <div id="row_status" class="row">
					                    <div id="quadro_evento" class="col-md-12 hidden">
					                        <div class="row">
					                            <div id="info_evento" class="col-xs-12">
					                            </div>
					                        </div>
					                        <br />
					                    </div>
					                    <div id="select_evento" class="col-md-12">
					                        <div class="form-group input-icon right">

					                            <div class="placeholder">Evento:</div>
					                            <input id="inputEvento" name="evento" type="text" placeholder="Evento"
					                                class="form-control  with-placeholder" autocomplete="off"
					                                onkeyup="busca_evento();" />
                                                <input id="inputEventoId" name="evento_id" type="hidden" />
                                                <input id="inputEventoNome" name="evento_nome" type="hidden" />
					                            <div id="autocp_evento" class="hidden autocp_div">
					                                <div id="div_loading_autocp" class="row loading_something hidden ">
					                                    <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
					                                </div>
					                            </div>
					                        </div>

					                    </div>
					                </div>
					            </div>

					            <div class="col-md-4 bd-lf">
					                <h3>Vendedor</h3>

					                <div id="row_status" class="row">
					                    <div id="quadro_vendedores" class="col-md-12 hidden">
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

					                            <div class="placeholder">Vendedor:</div>
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
					            <div class="col-md-4 bd-lf">

                                    <div class="row">
                                        <h3> Comprador 
                                            <span title="Fechar a Busca" onclick="fecharBuscaPessoa()">
                                                <i class="fa fa-minus-square fs-18 red pull-right"></i>
                                            </span>       
                                        </h3>                                 
                                    </div>

					                <div id="row_status" class="row">
					                    <div id="quadro_compradores" class="col-md-12 hidden">
					                        <div class="row">
					                            <div class="col-xs-4 col-sm-3 ">
					                                <img id="img_compradores" src="" class="img-responsive img-circle  wd-100p" />
					                            </div>
					                            <div id="info_compradores" class="col-xs-8 col-sm-9">
					                            </div>
					                        </div>
					                        <br />
					                    </div>
					                    <div id="select_compradores" class="col-md-12">
					                        <div class="form-group input-icon right">
					                            <div class="placeholder">Comprador:</div>
					                            <input id="inputComprador" name="comprador" type="text" placeholder="Comprador"
					                                class="form-control  with-placeholder" autocomplete="off"
					                                onkeyup="busca_pessoa('compradores');" />
					                            <input id="inputCompradorId" name="comprador_id" type="hidden" />
					                            <input id="inputCompradorNome" name="comprador_Nome" type="hidden" />
					                            <div id="autocp_compradores" class="hidden autocp_div">
					                                <div id="div_loading_autocp" class="row loading_something hidden ">
					                                    <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
					                                </div>
					                            </div>
					                        </div>

					                    </div>
					                </div>
					            </div>
					        </div>


                            <div class="form-group">
                                <h3>Observações:</h3>
                                <textarea id="observacao" name="observacao" class="form-control" rows="3" placeholder="Máximo de 500 caracteres..."></textarea>
                            </div>


                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <h3>Observações:</h3>
                                    <textarea id="observacao" name="observacao" rows="4" cols="500">
                                    </textarea>
                                </div>
                            </div> -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button id="btn-save-protocolo" name="btn-save-protocolo" type="button" class="btn btn-brown control_edit_protocolo_div"
                                    onClick="$('#form_protocolos').submit()">Salvar</button>
                            </div>


					    </div>
                        <input id="inputUsuario" name="usuario" type="hidden" />
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

<!-- modal troca setor - protocolos-->
<div class="modal fade" id="md_setor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-50p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_setor_edit"></h4>
            </div>
            <div class="modal-body" id="md_setor_bd">
                <div class="panel panel-bordo">
                    <div id="setor_header" class="panel-heading">
                        Informe o setor </div>
                    <div class="panel-body pan">


					<form id="form_setor" action="javascript:salvarTrocaSetor()">
                        <div class="form-body pal pd-tp-0">

                            <div class="row">
                                <h3 id="setor_sub_header" class="mg-tp-0">Selecione o Setor:</h3>
                                <div id="setor_form-group" class="form-group">
                                    <!-- <label id="setor_sub_header"></label> -->
                                    <select name="inputTrocaSetor" id="inputTrocaSetor" 
                                            class="form-control select2" multiple="multiple" data-placeholder="Setor"
                                            style="width: 100%;" size="3">
                                        <option value="Confirmação"> Confirmação </option>
                                        <option value="Contratos"> Contratos </option>
                                        <option value="Boletos"> Boletos </option>
                                        <option value="Jurídico"> Jurídico </option>
                                        <option value="Embriões"> Embriões </option>
                                        <option value="Advogados"> Advogados </option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>

                        </div>
                        <input id="inputTrocaSetorUsuario" name="setor_usuario" type="hidden" />
                        <input id="inputTrocaSetorProtocoloID" name="setor_protocolo_id" type="hidden" />

                        <button type="submit" class="hidden"></button>
					</form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="btn-save-trocasetor" type="button" class="btn btn-brown control_edit_protocolo_div"
                    onClick="$('#form_setor').submit()">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- fim md_setor -->

<!-- modal cancelar protocolos-->
<div class="modal fade" id="md_cancelar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-50p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_cancelar_edit"></h4>
            </div>
            <div class="modal-body" id="md_cancelar_bd">
                <div class="panel panel-red">
                    <div id="cancelar_header" class="panel-heading"></div>
                    <div class="panel-body pan">


					<form id="form_cancelar" action="javascript:salvarCancelar()">
                        <div class="form-body pal pd-tp-0">

                            <div class="row">
                                <h3 id="cancelar_sub_header" class="mg-tp-0">Selecione o Setor:</h3>
                                <div id="cancelar_form-group" class="form-group">
                                <!-- textarea -->
                                <div class="form-group">
                                    <!-- <label>Textarea</label> -->
                                    <textarea id="inputCancelar" name="inputCancelar" class="form-control" rows="3" placeholder="Máximo de 500 caracteres..."></textarea>
                                </div>

                                </div>
                                <!-- /.form-group -->
                            </div>

                        </div>
                        <input id="inputCancelarUsuario" name="cancelar_usuario" type="hidden" />
                        <input id="inputCancelarProtocoloID" name="cancelar_protocolo_id" type="hidden" />

                        <button type="submit" class="hidden"></button>
					</form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="btn-save-cancelar" type="button" class="btn btn-brown control_edit_protocolo_div"
                    onClick="$('#form_cancelar').submit()">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- fim md_setor -->

<!-- Modal Ocorrências -->
<?php include($raiz . "/adm/protocolos/modal_eventos_protocolos.php"); ?>


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

// Ativa CKEditor para o texto das ocorrências
window.onload = function() {
    CKEDITOR.replace('OcorMensagem');
};

var filtro_protocolo_id = "";
var filtro_status       = "";
var filtro_setor        = "";
var filtro_data         = "";
var filtro_prazo        = "";
var filtro_contrato_id  = "";
var filtro_vencimento   = "";

var filtro_vendedor     = "";
var filtro_comprador    = "";
var filtro_evento       = "";
var filtro_produto      = "";

var filtrar = 0;
var order = "id";
var ordem = "desc";

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

    $('#inputValor').maskMoney();

    $("#inputPrazo").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        minDate: 0,
		// maxDate: '0',
    });

    $("#inputDt_parcela").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#inputDt_contrato").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
        // minDate: 0,
		// maxDate: '0',
    });

    $("#inputDt_digitalizado").datepicker({
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
    var proto_title = 'Novo Protocolo';

    if (tipo == 'cadastro') {

        // Retorna o proximo dia útil daqui a 5 dias, ver inc/util.php
        dias_uteis = '<?php echo protocolos_feriados_range(); ?>';

        $("input#inputUsuario").val(usuario);

        $("input#inputId").val('');
        $("input#inputProtocolo").val(moment(data).format('YYMMDDHHmmss'));
        // $("input#inputData").val(ConverteData(data));
        $("input#inputData").val(moment(data).format('DD/MM/YYYY HH:mm:ss'));
        $("input#inputStatus").val('Pendente');
        $("input#inputSetor").val('Confirmação');
        $("input#inputVendedor").val('');
        $("input#inputVendedorId").val('');
        $("input#inputVendedorNome").val('');
        $("input#inputComprador").val('');
        $("input#inputCompradorId").val('');
        $("input#inputCompradorNome").val('');
        $("input#inputEvento").val('');
        $("input#inputProduto").val('');
        $("input#inputValor").val('');
        $("input#inputPrazo").val(moment(dias_uteis).format('DD/MM/YYYY'));
        $("input#inputDt_parcela").val(moment(data).format('DD/MM/YYYY'));
        $("input#inputNr_parcela").val('');
        $("textarea#observacao").val('');

        $("input#inputDt_contrato").val('');
        $("input#inputDt_digitalizado").val('');
        
        // Limpa os dados do vendedor e comprador
        limpa_pessoa(0);

        // Limpa os dados do evento
        limpa_evento();       

    } else if (tipo == 'editar') {
        proto_title = 'Protocolo Edição';
        // console.log('Dados: ' + JSON.stringify(dados));

        // Limpa os dados do vendedor e comprador
        limpa_pessoa(0);

        // Limpa os dados do evento
        limpa_evento();       

        $("input#inputId").val(id);
        $("input#inputUsuario").val(usuario);

        $("input#inputProtocolo").val(dados.protocolo);
        $("input#inputData").val(moment(dados.dt_registro).format('DD/MM/YYYY HH:mm:ss'));
        $("input#inputStatus").val(dados.status);
        $("input#inputSetor").val(dados.setor);
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
        $("input#inputPrazo").val(moment(dados.prazo).format('DD/MM/YYYY'));
        $("input#inputDt_parcela").val(moment(dados.dt_parcela).format('DD/MM/YYYY'));
        $("input#inputNr_parcela").val(dados.nr_parcela);
        $("#observacao").val(dados.observacao);

        if ( dados.dt_contrato ) {
            $("input#inputDt_contrato").val(moment(dados.dt_contrato).format('DD/MM/YYYY'));
        } else {
            console.log('Nulo')
            $("input#inputDt_contrato").val('');
        }

        if (dados.dt_digitalizado) {
            $("input#inputDt_digitalizado").val(moment(dados.dt_digitalizado).format('DD/MM/YYYY'));
        } else {
            $("input#inputDt_digitalizado").val('');
        }

        if( dados.evento.toUpperCase() == "VENDA DIRETA") {
            $("#div_venda_direta").removeClass("hidden");
        } else {
            $("#div_venda_direta").addClass("hidden");
        }

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
        $("input#inputStatus").val('Pendente');
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

        $("input#inputDt_contrato").val('');
        $("input#inputDt_digitalizado").val('');

        $("input#inputDt_verifica").val(dados.dt_verifica);

    }

    $("#cadastro_header").text(proto_title);

    $("#btn-save-protocolo").attr("disabled", false);
    $('#md_protocolos').modal('show');
}

function filtrar_fields() {
    filtro_protocolo_id = $('#filtro_protocolo_id').val();
    filtro_evento       = $('#filtro_evento').val();
    filtro_status       = $('#filtro_status').val();
    filtro_setor        = $('#filtro_setor').val();
    filtro_data         = $('#filtro_data').val();
    filtro_prazo        = $('#filtro_prazo').val();
    filtro_vendedor     = $('#filtro_vendedor').val();
    filtro_comprador    = $('#filtro_comprador').val();
    filtro_contrato_id  = $('#filtro_contrato_id').val();
    filtro_vencimento   = $('#filtro_vencimento').val();
    filtro_produto      = $('#filtro_produto').val();

    $('#tbody_protocolos').html('<tr><td colspan="10">Carregando protocolos</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

    carregar_resultados('Filtrar');
}

function limpa_filtros() {
    $('#filtro_protocolo_id').val('');
    $('#filtro_status').val('');
    $('#filtro_setor').val('');
    $('#filtro_data').val('');
    $('#filtro_prazo').val('');
    $('#filtro_vendedor').val('');
    $('#filtro_comprador').val('');
    $('#filtro_evento').val('');
    $('#filtro_vencimento').val('');
    $('#filtro_produto').val('');

    // $('#form_filtros_protocolos').parents('.input-group').removeClass('red');

    filtrar = 0;

    filtrar_fields();
}

function carregar_resultados(dados=null) {
    // Carrega os totais antes dos protocolos
    carregar_totais();

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
    // console.log("Protocolo_ID " + JSON.stringify(filtro_protocolo_id));

    $.getJSON('<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao=lista_protocolos"; ?>&inicial=' +
        exibidos, {
            order: order,
            ordem: ordem,
            filtrar: filtrar,
            filtro_protocolo_id: filtro_protocolo_id,
            filtro_data: filtro_data,
            filtro_prazo: filtro_prazo,
            filtro_status: filtro_status,
            filtro_setor: filtro_setor,
            filtro_vencimento: filtro_vencimento,
            filtro_vendedor: filtro_vendedor,
            filtro_comprador: filtro_comprador,
            filtro_evento: filtro_evento,
            filtro_produto: filtro_produto,
            
            filtro_pagina: 'protocolos',
            ajax: 'true'
        },
        function(j) {
            cont_novos = 0;
            novos = "";
            data_atual = "<?php echo date('Y-m-d'); ?>"
            obs = "";

            for (var i = 0; i < j.length; i++) {
                exibidos++;
                cont_novos++;

                //open tr
                protocolos_aux = JSON.stringify(j[i]);

                // Fundo padrão
                fundo_tr = '#ffffff';

                // Monta campo Status da tabela com a bolinhas
                // fundo_tr_status = '#78d5fa';
                fundo_tr_status = 'bg-light';
                tooltip_tr_status = '';
                ball_imagem  = '<?php echo $link . "/imagens/balls_blue.png"; ?>';

                if((j[i].status || "").toUpperCase() == 'PENDENTE') {
                    // fundo_tr_status = 'bg-blue';
                    ball_imagem  = '<?php echo $link . "/imagens/balls_blue.png"; ?>';
                    tooltip_tr_status = 'Pendente';
                }

                if(j[i].prazo == data_atual) {
                    // fundo_tr_status = 'bg-light-yellow';
                    ball_imagem  = '<?php echo $link . "/imagens/balls_yellow.png"; ?>';
                    tooltip_tr_status = 'Prazo Vence hoje';
                }

                if(j[i].prazo < data_atual) {
                    // fundo_tr_status = ' bg-info1 ';
                    ball_imagem  = '<?php echo $link . "/imagens/balls_red.png"; ?>';
                    tooltip_tr_status = 'Prazo Vencido';
                }

                if(j[i].finalizado !== null) {
                    if(j[i].finalizado_motivo == null){
                        // fundo_tr_status = ' bg-darken-1 ';
                        ball_imagem  = '<?php echo $link . "/imagens/balls_green.png"; ?>';
                        tooltip_tr_status = 'Finalizado';
                    } else {
                        // fundo_tr_status = ' bg-red ';
                        ball_imagem  = '<?php echo $link . "/imagens/balls_black.png"; ?>';
                        tooltip_tr_status = 'Cancelado';
                    }
                }

                if(j[i].status == 'Cancelado') {
                    fundo_tr_status = 'bg-white';
                } else if (j[i].setor == 'Confirmação') {
                    fundo_tr_status = 'bg-purple';
                } else if (j[i].setor == 'Boletos') {
                    fundo_tr_status = 'bg-olive';
                } else if (j[i].setor == 'Jurídico') {
                    fundo_tr_status = 'bg-fuchsia';
                } else if (j[i].setor == 'Contratos') {
                    fundo_tr_status = 'bg-orange';
                } else if (j[i].setor == 'Embriões') {
                    fundo_tr_status = 'bg-light-blue';
                } else if (j[i].setor == 'Advogados') {
                    fundo_tr_status = 'bg-yellow';
                }

                // Verifica se o contrato foi entregue com 7  dias
                data_vd = '<?php echo date("Y-m-d H:i:s", strtotime( "-6 days" ) ); ?>';
                if( j[i].evento.toUpperCase() == 'VENDA DIRETA' 
                    && (!j[i].dt_contrato || !j[i].dt_digitalizado)
                    && j[i].dt_registro < data_vd
                    && j[i].ct_verifica == 1
                ) {
                    fundo_tr_status = 'bg-red';
                }

                // Define a cor da ocorrência com base na data da última registrada.
                cor_ocorrencia = '#cccccc';
                data_ocorrencia = moment(j[i].dt_ocorrencia).format('YYYY-MM-DD');
                data_ontem = '<?php echo date("Y-m-d", strtotime( "-1 days" ) ); ?>';
                data_velho = '<?php echo date("Y-m-d", strtotime( "-2 days" ) ); ?>';

                if(j[i].finalizado !== null) {
                        cor_ocorrencia = 'lime';
                } else {
                    if(data_ocorrencia == data_atual){
                        cor_ocorrencia = 'blue';
                    } else if (data_ocorrencia == data_ontem) {
                        cor_ocorrencia = 'yellow';                        
                    } else if (data_ocorrencia <= data_velho) {
                        cor_ocorrencia = 'red';
                    }
                }

                // novos += '<tr style="background-color:' + fundo_tr + '" id="tr_' + j[i].id + '">';
                novos += '<tr class="' + fundo_tr_status + '" id="tr_' + j[i].id + '">';

                //td #
                // novos += '<td class="hidden-xs hidden-sm">';
                // novos += j[i].id;
                // novos += '</td>';

                // Status
                novos += '<td class="notThisOne hidden-xs hidden-sm" title="'+tooltip_tr_status+'" style="text-align: center; vertical-align: middle;" >';
                novos += '<img src="'+ball_imagem+'" alt="Italian Trulli" height="20" width="20">';
                novos += '</td>';

                // Protocolo
                // novos += '<td class="hidden-xs hidden-sm" style="text-align: center; vertical-align: middle; color:' + fundo_tr_status +'">';
                novos += '<td class="hidden-xs hidden-sm" style="text-align: center; vertical-align: middle;">';
                if(j[i].status == 'Cancelado') {
                    novos += '<s style="text-decoration-line: line-through; text-decoration-color: red;" >'+j[i].protocolo+'</s>';
                } else {
                    novos += j[i].protocolo;
                }
                novos += '</td>';

                // Data
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ConverteData(j[i].dt_registro);
                novos += '</td>';

                // Prazo
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ConverteData(j[i].prazo);
                novos += '</td>';

                // Vendedor
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].vendedor || "" );
                novos += '</td>';

                // Comprador
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].comprador || "" );
                novos += '</td>';

                // Evento
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].evento || "" );
                novos += '</td>';

                // Produto
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].produto || "" );
                novos += '</td>';

                // Valor 
                novos += '<td  class="nowrap">';
                novos += 'R$ ' + number_format(j[i].valor, 2);
                novos += '</td>';

                // Setor / Situação
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].setor || "");
                novos += '<br>' + ( j[i].status || "" );
                novos += '</td>';

                // Contratos - Venda direta
                novos += '<td class="hidden-xs hidden-sm">';
                // novos += ( ConverteData(j[i].dt_contrato) || "");
                novos += ( ConverteData(j[i].dt_contrato) || "-");
                novos += '<br>' + ( ConverteData(j[i].dt_digitalizado) || "-");
                novos += '</td>';

                // Contrato ID
                novos += '<td class="hidden-xs hidden-sm">';
                novos += ( j[i].contrato_id || "");
                novos += '</td>';



                //td acao
                novos += "<td class='nowrap'>";

                // Impressão  
                novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Impressão' data-original-title='Impressão' onClick='imprimir(" +
                    j[i].id 
                    + " )'; > <i class='fa fa-print fs-18' > </i></span> </a>";

                // Ocorrências
                novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ocorrência' data-original-title='Ocorrência' onClick='mostra_ocorrencias(" +
                    j[i].id + ", " + j[i].protocolo + ", \"" + j[i].setor + "\", "
                    + " )'; > <i class='fa fa-comments fs-18' style='color: "+ cor_ocorrencia +";' > </i></span> </a>";

                if (<?php echo $_SESSION['perfil_id']; ?> >= 1 || <?php echo $_SESSION['id']; ?> == 4666 ) {
                    // Copiar, permitodo ao Admin e ao usuário 4666
                    novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Copiar' data-original-title='Copiar' "
                    + " onClick='modal_cad_protocolos(" +
                        j[i].id + ", \"copiar\", " + JSON.stringify(j[i])
                        + " )'; > <i class='fa fa-copy blue fs-18' > </i></span> </a>";
                }

                // Verifica o status para mostrar as acções abaixo: 
                if (j[i].status != 'Cancelado' && j[i].status != 'Finalizado') {

                    // Troca de setor
                    if(j[i].setor != 'Boletos' || <?php echo $_SESSION['perfil_id']; ?> == 1) {
                        novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Troca Setor' data-original-title='Troca o Setor' " 
                            + " onClick='troca_setor(" +
                            j[i].id + ", " + j[i].protocolo + ", \"" + j[i].setor + "\", " + (i + 1)
                            + " )'; > <i class='fa fa-share red_light fs-18' > </i></span> </a>";
                    }
                    
                    // Finalizar
                    if( (j[i].contrato_id == null || j[i].contrato_id == '') && ( j[i].finalizado == null) && ( j[i].setor == 'Boletos') ) {
                        novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Finalizar' data-original-title='Finalizar' "
                        + " onClick='finalizar(" +
                            j[i].id + ", " + j[i].protocolo + ", " + (i + 1)
                            + " )'; > <i class='fa fa-check-square-o green fs-18' > </i></span> </a>";
                    }

                    // Remover - desativado
                    // if(<?php echo $_SESSION['perfil_id']; ?> == 1 ) {
                    //     novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Remover' data-original-title='Remover' "
                    //     + " onClick='remover(" +
                    //         j[i].id + ", " + j[i].protocolo + ", " + (i + 1)
                    //         + " )'; > <i class='fa fa-trash-o red fs-18' > </i></span> </a>";
                    // }

                    // Quebra de linha para evitar uma td muito comprida
                    novos += " <br> ";

                    if((<?php echo $_SESSION['perfil_id']; ?> == 1 || <?php echo $_SESSION['id']; ?> == 2695) && j[i].status != 'Finalizado') {

                        // Editar
                        novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' "
                        + " onClick='modal_cad_protocolos(" +
                            j[i].id + ", \"editar\", " + JSON.stringify(j[i])
                            + " )'; > <i class='fa fa-pencil-square-o blue fs-18' > </i></span> </a>";

                        // Cancelamento
                        novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Cancelar' data-original-title='Cancelar' "
                        + " onClick='cancelar(" +
                            j[i].id + ", " + j[i].protocolo + ", " + (i + 1)
                            + " )'; > <i class='fa fa-close red fs-18' > </i></span> </a>";
                    }

                }

                if (j[i].status == 'Finalizado' && <?php echo $_SESSION['id']; ?> == 4666) {
                    // obs = JSON.stringify(j[i].observacao);
                    // console.log('Obs: ' + obs);
                    // obs = obs.replace(/(?:\\r\\n|\\r|\\n|°)/g, '<br>');
                    // console.log('Obs: ' + obs);

                    // Move para sotor contratos  
                    novos += " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Move Contratos' data-original-title='Impressão' onClick='moverContrato(" +
                        j[i].id + ", " + j[i].protocolo + ", " + (i + 1) + ", " + j[i].contrato_id + ", \"" + obs + "\""
                        + " )'; > <i class='fa fa-arrow-left orange fs-18' > </i></span> </a>";
                }


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
    // Altera o valor do campo caso a order seja situação
    if (campo == 'situacao') {
        order = 'status';
    } else if (campo == 'dt_registro') {
        order = 'protocolo';
    }

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


/*  AJAX EVENTO */

function busca_evento() {
    palavra = $('#inputEvento').val();
    
    tam_palavra = palavra.length;
    if (tam_palavra >= 3) {
        $('#inputEventoId').val('NULL');
        $('#inputEventoNome').val('');
        $('#autocp_evento').removeClass('hidden');
        $('.loading_something').removeClass('hidden');
        if (delay_busca) {
            clearTimeout(delay_busca);
        }
        delay_busca = setTimeout(function() {
            $.get("<?php echo $link."/adm/eventos/lista_evento_autocomplete.ajax.php";?>", {
                palavra: palavra
            }, function(result) {

                if (result == '0') {
                    //limpa resultados div_loading
                    $('.autocp_div').html(div_loading_autocp);
                    $('.autocp_div').addClass('hidden');
                    click_condominos_control = 0;
                } else {
                    //exibe resultados
                    $('#autocp_evento').html(result);
                    $('#autocp_evento').removeClass('hidden');
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

function escolhe_autocomplete_evento(evento) {
    $('#inputEventoId').val(evento.id);
    $('#inputEventoNome').val(evento.nome);

    btn_limpa_evento = "";
    btn_limpa_evento =
        '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_evento();"></span>';

    evt = btn_limpa_evento + evento.nome;
    if (evento.tipo != null)
        evt += "<br>" + evento.tipo;
    if (evento.leiloeiro_nome != null)
        evt += "<br>Leiloeiro: " + evento.leiloeiro_nome;
    $("#info_evento").html(evt);


    $('#autocp_evento').addClass('hidden');
    $('#select_evento').addClass('hidden');
    $('#quadro_evento').removeClass('hidden');
}

function limpa_evento() {
    $('#inputEventoId').val('');
    $('#inputEvento').val('');
    $('#inputEventoNome').val('');
    $('#quadro_evento').addClass('hidden');
    $('#select_evento').removeClass('hidden');
}

function venda_direta() {
    $('#inputEventoId').val(1); // id evento venda direta
    vd =
        '<h3>Venda Direta<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_evento();"></span></h3>';
    $("#info_evento").html(vd);
    $('#select_evento').addClass('hidden');
    $('#quadro_evento').removeClass('hidden');
}

$("#form_protocolos").validate({
    rules: {
        produto: {
            required: true
        },
        valor: {
            required: true
        },
        prazo: {
            required: true
        },
        vendedor: {
            required: true
        },
        comprador: {
            required: true
        },
        evento: {
            required: true
        },
        dt_parcela: {
            required: true
        },
        nr_parcela: {
            required: true,
            number: true
        },
        // observacao: {
        //     required: true,
        //     // minlength e maxlength funcionam
        //     minlength: 3,
        //     maxlength: 500,
        //     // rangelength: [3, 5]
        // },        
    },
    messages: {
        produto: "* Informe o nome do Animal ou produto",
        valor: "* Informe o valor do contrato",
        prazo: "* Informe o prazo final",
        vendedor: "* Selecione o vendedor",
        comprador: "* Selecione o comprador",
        evento: "* Selecione o evento",
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
    protocolo      = $("input#inputProtocolo").val();
    dt_registro    = moment($("input#inputData").val(), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
    p_status       = $("input#inputStatus").val();
    setor          = $("input#inputSetor").val();
    produto        = $("input#inputProduto").val();
    prazo          = moment($("input#inputPrazo").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    valor          = $("input#inputValor").val();

    dt_parcela     = moment($("input#inputDt_parcela").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    nr_parcela     = $("input#inputNr_parcela").val();

    evento       = $("input#inputEvento").val();
    evento_id    = $("input#inputEventoId").val();
    evento_nome  = $("input#inputEventoNome").val();

    vendedor       = $("input#inputVendedor").val();
    vendedor_id    = $("input#inputVendedorId").val();
    vendedor_nome  = $("input#inputVendedorNome").val();

    comprador      = $("input#inputComprador").val();
    comprador_id   = $("input#inputCompradorId").val();
    comprador_nome = $("input#inputCompradorNome").val();

    observacao     = $("#observacao").val();

    if ($("input#inputDt_contrato").val()) {
        dt_contrato = moment($("input#inputDt_contrato").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    } else {
        dt_contrato = 'NULL';
    } 

    if ($("input#inputDt_digitalizado").val()) {
        dt_digitalizado = moment($("input#inputDt_digitalizado").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
    } else {
        dt_digitalizado = 'NULL';
    } 



    if(acao == 'atualizar' && observacao == '') {
        observacao = ' ';
    }
    // Verifica se deve usar o nome do evento do cadastrado
    if(evento_id !== undefined && evento_id !== null && evento_id !== '' && evento_id != 'NULL') {
        evento = evento_nome;
    }

    // Verifica se deve usar o nome do vendedor do cadastrado
    if(vendedor_id !== undefined && vendedor_id !== null && vendedor_id !== '' && vendedor_id != 'NULL') {
        vendedor = vendedor_nome;
    }

    // Verifica se deve usar o nome do comprador do cadastrado
    if(comprador_id !== undefined && comprador_id !== null && comprador_id !== '' && comprador_id != 'NULL') {
        comprador = comprador_nome;
    }

    protocolos = [
        { name: 'id', value: id },
        { name: 'cad_pessoa', value: usuario },
        { name: 'protocolo', value: protocolo },
        { name: 'dt_registro', value: dt_registro },
        { name: 'prazo', value: prazo },
        { name: 'status', value: p_status },
        { name: 'setor', value: setor },
        { name: 'produto', value: produto },
        { name: 'valor', value: valor },
        { name: 'dt_parcela', value: dt_parcela },
        { name: 'nr_parcela', value: nr_parcela },
        { name: 'evento', value: evento },
        { name: 'evento_id', value: evento_id },
        { name: 'vendedor', value: vendedor },
        { name: 'vendedor_id', value: vendedor_id },
        { name: 'comprador', value: comprador },
        { name: 'comprador_id', value: comprador_id },
        { name: 'observacao', value: observacao },
        { name: 'dt_contrato', value: dt_contrato },
        { name: 'dt_digitalizado', value: dt_digitalizado },
    ];

    $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao="; ?>" + acao, {
        protocolos: protocolos
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

            document.getElementById("cont_exibidos").value = 0;
            order = "id";
            ordem = "desc";
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
    // window.open("<?php echo $link . "/repositories/protocolos/protocolos.rpt.php?protocolo_id="; ?>"+protocolo_id, 'name'); 
    window.open("<?php echo $link . "/repositories/protocolos/protocolos.rpt.php?protocolo_id="; ?>"+protocolo_id, '_blank'); 
}



function finalizar(protocolo_id=null, protocolo=null, indice=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    // alert('Proto ' + protocolo_id + " " + protocolo + " " + indice)

    $("#finalizar_header").text('Finalizando o protocolo ' + protocolo);
    $("#inputFinalizarUsuario").val(usuario);
    $("#inputFinalizarProtocoloID").val(protocolo_id);
    $("#inputFinalizarContratoID").val('');
    
    $('#md_finalizar').modal('show');

}

$("#form_finalizar").validate({
    rules: {
        contrato_id: {
            required: true,
            number: true,
        },
    },
    messages: {
        contrato_id: "* Informe o número do contrato gerado para esse protocolo",
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

function salvarFinalizar() {
    $('#btn-save-finalizar').addClass('hidden');
    protocolo_id = $("input#inputFinalizarProtocoloID").val();
    contrato_id  = $("input#inputFinalizarContratoID").val();
    usuario      = $("input#inputFinalizarUsuario").val();

    acao = 'finalizar';

    $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao="; ?>" + acao, {
        protocolo_id: protocolo_id,
        contrato_id: contrato_id,
        usuario: usuario,
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

            document.getElementById("cont_exibidos").value = 0;
            order = "id";
            ordem = "desc";
            if (acao == 'finalizar') {
                carregar_resultados(result.status);
            } else {
                carregar_resultados();
            }

            $('#md_finalizar').modal('hide');

            if (result.status == '9') {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            } else {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            }
        } else {
            jAlert(result.msg, 'Não foi possível finalizar o protocolo!', 'alert');
        }
    });
    $('#btn-save-finalizar').removeClass('hidden')
}

function fecharBuscaPessoa(){
    $('.autocp_div').addClass('hidden');
}

function remover(protocolo_id=null, protocolo=null, indice=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    acao = 'remover';

    jConfirm('',
                'Deseja remover o protocolo: '+protocolo+'?',
            function(r) {
                if (r) { 
                    $.getJSON("<?php echo $link."/repositories/protocolos/protocolos.ctrl.php?acao=";?>" + acao, 
						{protocolo_id: protocolo_id,
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

function troca_setor(protocolo_id=null, protocolo=null, setor=null, indice=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    // Reseta o formulário
    $("#form_setor").validate().resetForm();
    $('#setor_form-group').removeClass('red');

    // Ajusta texto das Lables
    $("#setor_header").text('Alterar setor do protocolo ' + protocolo);
    $("#setor_sub_header").text('Setor atual: '+setor);

    $("#inputTrocaSetorUsuario").val(usuario);
    $("#inputTrocaSetorProtocoloID").val(protocolo_id);
    // $("#inputTrocaSetor").val(setor).change();

    // Habilita todas as opções evitando erro ao rechamar o modal
    $("#inputTrocaSetor option").prop('disabled', false);


    $('#inputTrocaSetor option[value="'+setor+'"]').attr("disabled", true);

    if(setor == 'Jurídico') {
        $('#inputTrocaSetor option[value="Contratos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Boletos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Embriões"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Advogados"]').attr("disabled", true);
    } else if(setor == 'Boletos') {
        $('#inputTrocaSetor option[value="Contratos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Jurídico"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Embriões"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Advogados"]').attr("disabled", true);
    } else if(setor == 'Contratos') {
        $('#inputTrocaSetor option[value="Boletos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Jurídico"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Embriões"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Advogados"]').attr("disabled", true);
    } else if(setor == 'Embriões') {
        $('#inputTrocaSetor option[value="Boletos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Jurídico"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Contratos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Advogados"]').attr("disabled", true);
    } else if(setor == 'Advogados') {
        $('#inputTrocaSetor option[value="Boletos"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Jurídico"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Embriões"]').attr("disabled", true);
        $('#inputTrocaSetor option[value="Contratos"]').attr("disabled", true);
    }
 
    $('#md_setor').modal('show');

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


function salvarTrocaSetor() {
    $('#btn-save-trocasetor').addClass('hidden');
    protocolo_id = $("input#inputTrocaSetorProtocoloID").val();
    setor        = $( "#inputTrocaSetor option:selected" ).val();
    usuario      = $("input#inputTrocaSetorUsuario").val();

    acao = 'troca_setor';

    $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao="; ?>" + acao, {
        protocolo_id: protocolo_id,
        setor: setor,
        usuario: usuario,
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

            document.getElementById("cont_exibidos").value = 0;
            order = "id";
            ordem = "desc";
            if (acao == 'troca_setor') {
                carregar_resultados(result.status);
            } else {
                carregar_resultados();
            }

            $('#md_setor').modal('hide');
            $('#btn-save-trocasetor').removeClass('hidden');

            if (result.status == '9') {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            } else {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            }
        } else {
            jAlert(result.msg, 'Não foi possível trocar o setor do protocolo!', 'alert');
        }
    });
    $('#btn-save-trocasetor').removeClass('hidden');
}

function cancelar(protocolo_id=null, protocolo=null, setor=null, indice=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    // // Reseta o formulário
    $("#form_cancelar").validate().resetForm();
    // $('#cancelar_form-group').removeClass('red');

    // Ajusta texto das Lables
    $("#cancelar_header").text('Cancelamento do protocolo ' + protocolo);
    $("#cancelar_sub_header").text('Informe o motivo do cancelamento');

    $("#inputCancelarUsuario").val(usuario);
    $("#inputCancelarProtocoloID").val(protocolo_id);
    // $("#inputCancelar").val('').change();
    $("#inputCancelar").val('');


    $('#md_cancelar').modal('show');

}

$("#form_cancelar").validate({
    rules: {
        inputCancelar: {
            required: true,
            // minlength e maxlength funcionam
            minlength: 3,
            maxlength: 500,
            // rangelength: [3, 5]
        },
    },
    messages: {
        inputCancelar: {
            required: " * Informe o motivo do cancelamento!",
            minlength: jQuery.format(" * Use no mínimo {0} characters!"),
            maxlength: jQuery.format(" * Use no máximo {0} characters!"),
            rangelength: jQuery.format("Entre com pelo menos {0} characters e no maximo com {1} caracteres!"),
        }
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

function salvarCancelar() {
    $('#btn-save-cancelar').addClass('hidden');
    protocolo_id = $("input#inputCancelarProtocoloID").val();
    usuario      = $("input#inputCancelarUsuario").val();
    motivo       = $("#inputCancelar").val();

    acao = 'cancelar';

    $.getJSON("<?php echo $link . "/repositories/protocolos/protocolos.ctrl.php?acao="; ?>" + acao, {
        protocolo_id: protocolo_id,
        motivo: motivo,
        usuario: usuario,
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

            document.getElementById("cont_exibidos").value = 0;
            order = "id";
            ordem = "desc";
            if (acao == 'cancelar') {
                carregar_resultados(result.status);
            } else {
                carregar_resultados();
            }

            $('#md_cancelar').modal('hide');
            $('#btn-save-cancelar').removeClass('hidden');

            if (result.status == '9') {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            } else {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            }
        } else {
            jAlert(result.msg, 'Não foi possível cancelar o protocolo!', 'alert');
        }
    });
    $('#btn-save-cancelar').removeClass('hidden');
}

function gerar_planilha_protocolos() {
    var total_results = 1;
    if (total_results > 5000) {
        jAlert('Você está tentando gerar uma planilha com mais ' + total_results +
            ' registros.<br>Para não prejudicar seu processo limite a sua consulta a até 5000 registros.', 'Oops');
    } else {
        direct = '<?php echo $link . "/adm/protocolos/gera_planilha_protocolos.php"; ?>?order=' + order + '&ordem=' +
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
        filtro_protocolo_id: filtro_protocolo_id,
        filtro_data: filtro_data,
        filtro_prazo: filtro_prazo,
        filtro_status: filtro_status,
        filtro_setor: filtro_setor,
        filtro_vencimento: filtro_vencimento,
        filtro_vendedor: filtro_vendedor,
        filtro_comprador: filtro_comprador,
        filtro_evento: filtro_evento,
        filtro_produto: filtro_produto,

        
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
        filtro_protocolo_id: {
            number: true
        },
    },
    messages: {
        filtro_protocolo_id: "* Informe apena números",
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

function moverContrato(protocolo_id=null, protocolo=null, indice=null, contrato_id=null, observacao=null) {
    var usuario = "<?php echo $_SESSION['id']; ?>";
    acao = 'mover_contrato';

    jConfirm('',
                'Deseja Mover o protocolo: '+protocolo+'?',
            function(r) {
                if (r) { 
                    $.getJSON("<?php echo $link."/repositories/protocolos/protocolos.ctrl.php?acao=";?>" + acao, 
						{   protocolo_id: protocolo_id,
                            contrato_id: contrato_id,
                            observacao: observacao,
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

</script>

</body>

</html>
