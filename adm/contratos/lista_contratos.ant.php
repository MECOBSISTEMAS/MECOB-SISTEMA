<?php
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");

$menu_active = "contratos";
$layout_title = "MECOB - Contratos";
$sub_menu_active = "contratos";
$tit_pagina = "Contratos";
$tit_lista = " Lista de contratos";
// var_dump($_REQUEST);
// exit;
if (!consultaPermissao($ck_mksist_permissao, "cad_contratos", "qualquer")) {
    header("Location: " . $link . "/401");
    exit;
}

$addcss = '<link rel="stylesheet" href="' . $link . '/css/smoothjquery/smoothness-jquery-ui.css">';

include($raiz . "/partial/html_ini.php");

include_once($raiz . "/inc/util.php");

?>

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
                    <li class="hidden"><a href="#">Contratos</a>&nbsp;&nbsp;<i
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
                        <div class="col-lg-12">
                            <div class="panel panel-bordo" style="background:#FFF;">
                                <div class="panel-heading"><?php echo $tit_lista; ?></div>
                                <div class="panel-body">
                                    <?php 

                                    if (consultaPermissao($ck_mksist_permissao, "cad_contratos", "adicionar")) { ?>
                                    <h3><button type="button" class="btn btn-brown"
                                            onClick="alimenta_modal_cad_contratos(0,'','adimplencia');">
                                            Novo Contrato de Adimplência</button>

                                        <button type="button" class="btn btn-brown"
                                            onClick="alimenta_modal_cad_contratos(0,'','inadimplencia');">
                                            Novo Contrato de Inadimplência</button>

                                    </h3>
                                    <?php 
                                }
                                include($raiz . "/adm/contratos/filtros_contratos.php");    ?>
                                    <div id="linha_totais"></div>
                                    <span onMouseOver='$( "#bt_legenda" ).click();'
                                        onMouseOut='$( "#bt_legenda" ).click();' id="bt_legenda"
                                        class="fa fa-th-list fs-24 pull-right mg-lf-5 mg-tp-12 gray_system"
                                        data-placement="left" data-toggle="popover" title="Legenda dos Ícones"
                                        data-html="true" data-content='
<div class="leg_ico_geral3">
<div class="leg_ico_unico">
<span class="fa fa-pencil-square-o"></span> - Editar/Visualizar<br>
<span class="fa fa-copy"></span> - Copiar Contrato<br>
<span class="fa fa-list-ol"></span> - Parcelas/Simulação<br>
<span class="fa fa-file-o"></span> - Arquivos <br>
<span class="fa fa-comments"></span> - Ocorrências<br>
<span class="fa fa-usd"></span> - Boletos <br> 
<span class="fa fa-star"></span> - Ver Contrato Original<br>
<span class="fa fa-star-o"></span> - Ver Novo Contrato <br>
<span class="fa fa-exclamation-triangle"></span> - Parcelas vencidas mais 5 dias <br>
</div>
</div>
'></span>
                                    <div id="listagem">
                                        <table id="listagem_contratos" class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th id="th_id" class="hidden-xs hidden-sm pointer"
                                                        onclick="ordenar('id');">Id <i
                                                            class="fa fa-arrow-circle-down fl-rg ico_ordem"></i></th>

                                                    <th id="th_descricao" class="pointer"
                                                        onclick="ordenar('descricao');">Contrato</th>
                                                    <th id="th_data" class="pointer hidden-xs hidden-sm "
                                                        onclick="ordenar('data');">Data</th>

                                                    <th id="th_valor" class="pointer " onclick="ordenar('valor');">Valor
                                                    </th>
                                                    <th id="th_evento" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('evento');">Evento</th>
                                                    <th id="th_vendedor" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('vendedor');">Vendedor</th>
                                                    <th id="th_comprador" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('comprador');">Comprador</th>
                                                    <th id="th_status" class="pointer hidden-xs hidden-sm"
                                                        onclick="ordenar('status');">Status</th>


                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_contratos">
                                                <tr>
                                                    <td id="td_carregando" colspan="10">Carregando contratos</td>
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

<!-- modal cadastro de contratos-->
<div class="modal fade" id="md_cadastro_contratos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_cadastro_contratos_tt"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_contratos_bd">
                <div class="panel panel-bordo">
                    <div class="panel-heading">
                        Cadastro de <?php echo $tit_pagina; ?></div>
                    <div class="panel-body pan">
                        <?php include($raiz . "/adm/contratos/form_contratos.php"); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="btn-save-contrato" type="button" class="btn btn-brown control_edit_contrato_div"
                    onClick="$('#form_contratos').submit()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="md_confirma_suspensao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Suspensão</h4>
            </div>
            <div class="modal-body" id="md_documentos_bd">
                <div class="panel panel-bordo">

                    <div class="panel-body pan">
                        
                        <div class="row">
                            <div class="col-sm-12 text-center">
                            <h4>Confirmar a suspensão do contrato <?php echo $_REQUEST['id'];?>?</h4>
                            </div>
                        </div>
                        <div class="row"><div class="col-sm-12">
                            <?php 
                                if (isset($_REQUEST['motivo'])){
                                    echo "Motivo:<br>";
                                    echo $_REQUEST['motivo'];
                                }
                            ?>
                        </div></div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input class="form-control" placeholder="Motivo (opcional)" id="respostaSuspensao">
                            </div>
                        </div>
                        <div class="row"><div class="col-sm-12">&nbsp;</div></div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" onclick="respostaSuspensaoContrato('S');">Sim</button>
                                <button class="btn btn-danger" onclick="respostaSuspensaoContrato('N');">Não</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<!-- modal edit parcelas-->

<div class="modal fade" id="md_edit_parcelas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_edit_parcelas_tt"></h4>
            </div>
            <div class="modal-body pd-0" id="md_edit_parcelas_bd">
                <div class="panel panel-bordo mg-0">
                    <div class="panel-heading" id="md_edit_parcelas_bd_head">
                    </div>
                    <div class="panel-body ">
                        <div id="md_edit_parcelas_bd_body">
                        </div>
                        <div id="md_edit_parcelas_bd_body_form">
                        </div>
                        <div id="md_edit_instrucoes" class="hidden">
                            <hr />
                            <input type="hidden" id="inputInstrucaoCtId" name="inputInstrucaoCtId" />
                            <h4>Instruções personalizadas:</h4>
                            1:<br />
                            <input type="text" id="inputInstrucao1" name="inputInstrucao1" class="form-control" />
                            2:<br />
                            <input type="text" id="inputInstrucao2" name="inputInstrucao2" class="form-control" />
                            3:<br />
                            <input type="text" id="inputInstrucao3" name="inputInstrucao3" class="form-control" />
                            <br />


                            <button type="button" class="btn btn-danger"
                                onClick="javascript:cancel_customizar_instrucoes();">Cancelar</button>
                            <button type="button" class="btn btn-warning"
                                onClick="javascript:save_customizar_instrucoes(2);">Sem instruções</button>
                            <button type="button" class="btn btn-primary"
                                onClick="javascript:save_customizar_instrucoes(1);">Instruções padrão</button>
                            <button type="button" class="btn btn-success"
                                onClick="javascript:save_customizar_instrucoes(3);">Salvar instruções</button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btInstrucoes" type="button" class="btn btn-info fl-lf"
                    onClick="customizar_instrucoes();">Customizar Instruçoes</button>


                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="btZerarParcelas" type="button" class="btn btn-brown"
                    onClick="javascript:modal_zerar_parcelas()">Zerar parcelas em aberto</button>
                <button id="btSalvarParcelas" type="button" class="btn btn-brown"
                    onClick="$('#form_edit_parcela').submit()">Salvar parcelas e continuar depois</button>
                <button id="btConfirmarContrato" type="button" class="btn btn-brown"
                    onClick="javascript:confirmar_contrato()">Salvar parcelas e confirmar contrato</button>
                <button id="btConfirmarAcordo" type="button" class="btn btn-brown"
                    onClick="javascript:confirmar_acordo()">Salvar parcelas e confirmar acordo</button>
                <button id="btVirarInadimplente" type="button" class="btn btn-brown"
                    onClick="javascript:virar_inadimplente()">Transformar em inadimplente</button>
                <button id="btVirarAcaoJudicial" type="button" class="btn btn-brown"
                    onClick="javascript:virar_acao_judicial()">Transformar em Ação Judicial</button>
            </div>
        </div>
    </div>
</div>


<!-- modal de documentos -->
<div class="modal fade" id="md_documentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_documentos_tt">Documentos</h4>
            </div>
            <div class="modal-body" id="md_documentos_bd">
                <div class="panel panel-bordo">

                    <div class="panel-body pan">
                        <div class="pd-lf-15">
                            <h4>Novo documento</h4>

                            <form id="form_documento" name="form_imagem"
                                action="<?php echo $link . "/repositories/contratos/contratos.ctrl.php"; ?>"
                                method="post" enctype="multipart/form-data">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="form-group input-icon right">
                                            <input id="inputDocContato" name="contratos_id" type="hidden" />
                                            <input type="hidden" id="acao" name="acao" value="upload_file" />
                                            <input id="inputDoc" name="arquivo" type="file" placeholder="Documento"
                                                class="form-control   fl-lf mg-bt-10" required="required" />
                                            <input id="inputDocDesc" name="descricao" type="text"
                                                placeholder="Descricao" class="form-control   fl-lf mg-bt-10"
                                                required="required" />
                                            <button type="submit" class="btn  btn-primary fl-lf">Inserir</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="uploading_doc hidden">
                                <h4>Enviando arquivo</h4>
                                <br>
                                <div><span id="kb_upado"></span></div>
                                <br>
                                <div class=" progress progress-striped">
                                    <div id="bar_up_foto" class="progress-bar progress-bar-success  " role="progressbar"
                                        aria-valuenow="00" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        0%
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <table id="list_docs" class="table table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_docs">
                                    <tr>
                                        <td id="td_carregando_docs" colspan="10">Carregando documentos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info fl-lf" onclick="md_termo_divida();">PDF Termo confissão
                    dívida</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal boleto com mais de 60 dias de atraso -->
<div class="modal fade" id="md_boleto_recalculado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_documentos_tt">Recalcular e gerar novo boleto</h4>
            </div>
            <div class="modal-body" id="md_boleto_recalculado_bd">
                <div class="panel panel-bordo">
                    <div class="panel-body pan">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12"><label for="juros_boleto_recalculado">Juros a.m.
                                                (%)</label></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>%</h4>
                                        </div>
                                        <div class="col-md-10"><input type="number" step="0.01" class="form-control"
                                                aria-describedby="jurosHelp" id="juros_boleto_recalculado"
                                                placeholder="Ex.: 2.00"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><small id="jurosHelp" class="form-text text-muted">Juros
                                                ao mês a ser aplicado</small></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="multa_boleto_recalculado_reais">Multa</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>R$</h4>
                                        </div>
                                        <div class="col-md-8"><input type="number" step="0.01" class="form-control"
                                                aria-describedby="jurosHelp" id="multa_boleto_recalculado_reais"
                                                placeholder="Ex.: 20.00"></div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>%</h4>
                                        </div>
                                        <div class="col-md-8"><input type="number" step="0.01" class="form-control"
                                                aria-describedby="multaHelp" id="multa_boleto_recalculado_porcentagem"
                                                placeholder="Ex.: 2.50"></div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <small id="multaHelp" class="form-text text-muted">Multa a ser aplicada, em reais ou
                                        porcentagem</small>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12"><label
                                                for="vencimento_boleto_recalculado">Vencimento</label></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><input type="date" class="form-control"
                                                aria-describedby="vencimentoHelp" id="vencimento_boleto_recalculado">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><small id="vencimentoHelp"
                                                class="form-text text-muted">Vencimento da parcela</small></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="taxas_boleto_recalculado_reais">Taxas</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>R$</h4>
                                        </div>
                                        <div class="col-md-8"><input type="number" step="0.01" class="form-control"
                                                aria-describedby="taxasHelp" id="taxas_boleto_recalculado_reais"
                                                placeholder="Ex.: 20.00"></div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <small id="taxasHelp" class="form-text text-muted">Taxas a serem aplicada, em
                                        reais</small>
                                </div>
                                <div class="form-group">
                                    <label for="honorarios_boleto_recalculado_porcentagem">Honorários</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>%</h4>
                                        </div>
                                        <div class="col-md-8"><input type="number" step="0.01" class="form-control"
                                                aria-describedby="honorariosHelp"
                                                id="honorarios_boleto_recalculado_porcentagem" placeholder="Ex.: 20.00">
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <small id="honorariosHelp" class="form-text text-muted">Honorários a serem
                                        aplicados, em porcentage</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_boleto_recalculado">Total</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h4>R$</h4>
                                        </div>
                                        <div class="col-sm-10">
                                            <input type="number" step="0.01" readonly class="form-control"
                                                aria-describedby="jurosHelp" id="total_boleto_recalculado"><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Valor original:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_total">
                                            R$ 0,00
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Correção monetária:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_correcao">
                                            R$ 0,00
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Juros:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_juros">
                                            R$ 0,00
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Multa:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_multa">
                                            R$ 0,00
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Taxas:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_taxas">
                                            R$ 0,00
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Honorários:
                                        </div>
                                        <div class="col-md-6" id="total_boleto_recalculado_honorarios">
                                            R$ 0,00
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info fl-lf" id="gerar_novo_boleto">Gerar novo boleto</button>
            </div>
        </div>
    </div>
</div>
<!-- fim modal boleto com mais de 60 dias de atraso -->



<div class="modal fade" id="mdGerandoBoletos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div class="panel-body pan">
                        <div class="pd-lf-15 ac">
                            <h2>Aguarde</h2>
                            <h4> <img src="<?php echo $link . "/imagens/spining.gif"; ?>" width="28px;" /> </h4>
                            <h3>Gerando boletos</h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdMotivoSuspensao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div class="panel-body pan">
                        <div class="pd-lf-15 ac">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group input-icon right">
                                        <strong>Motivo da suspensão:</strong>
                                        <br />
                                        <input type="text" id="motivoSuspensao" name="motivoSuspensao"
                                            class="form-control " />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="informa_motivo_suspensao();">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdTermoDivida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div id="mdTermoDivida_bd" class="panel-body pan">
                        <div id="mdTermoDivida_bd_info">
                        </div>
                        <form id="form_termoDivida" action="javascript:salvar_termodivida()">
                            <input type="hidden" id="TermoContratoId" name="id" />

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group input-icon right">
                                        <strong>Percentual Contrato:</strong>
                                        <br />
                                        <input type="text" id="TermoPercentual" name="termo_percentual_contrato"
                                            class="form-control " />
                                        <br />
                                        <span>*ex: "100%"</span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <strong>Descrição Fiador</strong>
                                        <br />
                                        <textarea id="fiador" name="fiador"
                                            class="form-control " placeholder="Ex.: Pedro de Alcântara, brasileiro, inscrito no CPF sob o nº 111.111.111-11, residente e domiciliado na Rua da Independência, nº 111, São Paulo/SP - CEP 1111-111"></textarea>
                                        <small>Deixe em branco para não adicionar fiador</small>
                                        <br />
                                </div>
                                <div class="col-sm-12">
                                    <strong>Descriçao do animal*</strong>
                                        <br />
                                        <textarea id="animal" required name="animal"
                                            class="form-control " placeholder="Ex.: 50% (cinquenta por cento) das cotas do equino, da espécie Mangalarga Marchador, denominado 'King RRC' (registro n. 111111 - ABCCMM)"></textarea>
                                        <small>Deixe em branco para não adicionar a descrição do animal</small>
                                        <br />
                                </div>
                                <!-- <div class="col-sm-12">
                                    <div class="form-group input-icon right">
                                        <strong>Descrição Pagto</strong>
                                        <br />
                                        <textarea id="TermoDescPagto" name="termo_descricao_pagto"
                                            class="form-control "></textarea>
                                        <span>*ex: "20 (vinte) parcelas iguais e sucessivas, na monta de R$ 1.750,00
                                            (hum mil setecentos e cinquenta reais), com início em 28/12/2016 e término
                                            em 28/07/2018."</span>
                                        <br />
                                    </div>
                                </div> -->
                                <div class="col-sm-12">
                                    <div class="form-group input-icon right">
                                        <strong>Local e data</strong> <br />
                                        <input type="text" id="TermoLocalData" name="termo_local_data"
                                            class="form-control " />
                                        <br />
                                        <span>*ex: "Itajaí/SC, 29 de novembro de 2016."</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="cancela_termoDivida();">Cancelar</button>
                            <button id="btn-save-termo" type="submit" class="btn btn-sm btn-success">Gerar PDF</button>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="salvar_termodivida_world();">Gerar .doc</button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal de documentos -->
<div class="modal fade" id="md_ocorrencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="md_ocorrencias_tt"></h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div class="panel-body pan">
                        <div id="md_ocorrencias_add" class="pd-lf-15">
                            <h2 class="mg-tp-0">Ocorrências</h2>
                            <button id="btnAddOcor" type="button" class="btn btn-sm btn-success"
                                onclick="nova_ocorrencia();"
                                style="    position: absolute;  right: 25px;    top: 25px;"><i class="fa fa-plus"></i>
                                Nova</button>
                            <div id="divAddOcor" class="hidden">
                                <form id="form_ocorrencia" action="javascript:salvar_ocorrencia()">
                                    <input type="hidden" id="OcorContrato" name="contratos_id" />
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group input-icon right">
                                                <div class="placeholder">Status:</div>
                                                <select id="OcorStatus" name="status"
                                                    class="form-control with-placeholder" required>
                                                    <option value=""> Selecione o Status </option>
                                                    <option value="Recado"> Recado </option>
                                                    <option value="Não atende"> Não atende </option>
                                                    <option value="Promessa de pagamento"> Promessa de pagamento
                                                    </option>
                                                    <option value="Para pesquisa"> Para pesquisa </option>
                                                    <option value="Retorna cobrança"> Retorna cobrança </option>
                                                    <option value="Solicitação de prazo"> Solicitação de prazo </option>
                                                    <option value="Notificação extrajudicial 01"> Notificação
                                                        extrajudicial 01 </option>
                                                    <option value="Notificação extrajudicial 02"> Notificação
                                                        extrajudicial 02 </option>
                                                    <option value="Notificação extrajudicial 03"> Notificação
                                                        extrajudicial 03 </option>
                                                    <option value="Local incerto"> Local incerto </option>
                                                    <option value="Ação judicial"> Ação judicial </option>
                                                    <option value="E-mail de cobrança"> E-mail de cobrança </option>
                                                    <option value="Whatsapp de cobrança"> Whatsapp de cobrança </option>
                                                    <option value="SMS de cobrança"> SMS de cobrança </option>
                                                    <option value="Outros"> Outros </option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                        </div>
                                    </div>
                                    <div class="row hidden" id="promessaPagamentoDiv">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="promessaPagamento">Data da promessa de pagamento:</label>
                                                <input type="date" class="form-control" id="promessaPagamento" name="promessa_pagamento">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group input-icon right">
                                                <textarea id="OcorMensagem" name="mensagem"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="cancela_ocorrencia();">Cancelar</button>
                                    <button id="btn-save-ocor" type="submit"
                                        class="btn btn-sm btn-success">Salvar</button>


                                </form>
                                <hr>
                            </div>


                        </div>
                        <div id="md_ocorrencias_bd" class="pd-lf-15 mg-tp-30">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- modal cadastro de contratos-->
<div class="modal fade" id="md_cadastro_edit_desc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_cadastro_edit_desc_tt"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_edit_desc_bd">
                <div class="panel ">
                    <div class="">
                        Editar Contrato
                        <div class="panel-body pan">
                            <form id="form_edit_contrato" action="javascript:salvarEditDesc()">
                                <input id="inputIdEditDesc" type="hidden" name="id" placeholder="Id"
                                    class="form-control" />
                                <div class="form-body pal pd-tp-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group input-icon right">
                                                <div class="placeholder">Nova descrição do contrato:</div>
                                                <input id="inputEditDescricao" name="descricao" type="text"
                                                    placeholder="Nova descrição"
                                                    class="form-control  with-placeholder control_edit_contrato_input"
                                                    required="required" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="hidden"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-brown "
                        onClick="$('#form_edit_contrato').submit()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal zerar parcelas suspensao-->
<div class="modal fade" id="md_cadastro_zera_parcelas_suspensao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a id="go_to_md_cadastro_zera_parcelas_suspensao" class="smoothscroll hidden"
                    href="#md_cadastro_zera_parcelas_suspensao_tt"></a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_cadastro_zera_parcelas_suspensao_tt"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_zera_parcelas_suspensao_bd">
                <div class="panel ">
                    <div class="">
                        Editar Contrato
                        <div class="panel-body pan">
                            <form id="form_zera_parcelas_suspensao">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group input-icon right">
                                            <div class="placeholder">Status:</div>
                                            <select id="selectMotivoZerado" name="motivo_zerado"
                                                class="form-control with-placeholder">
                                                <option value=""> Selecione o Motivo </option>
                                                <option value="Pagamento direto para o cliente">Pagamento direto para o
                                                    cliente</option>
                                                <option value="Abatimento de parcela">Abatimento de parcela</option>
                                                <option value="Cancelamento">Cancelamento</option>
                                                <option value="Outros">Outros</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group input-icon right">
                                            <textarea id="textareaObservacaoZerado" name="observacao_zerado"
                                                style="    width: 100%;   height: 250px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="hidden"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="inputIdParcela" type="hidden" name="parcela_id" />
                    <input id="inputIdContrato" type="hidden" name="inputIdContrato" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-brown " onClick="javascript:zerar_parcelas_suspensao()">Zerar
                        Parcelas</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- fim cadastro de pessoas-->
<?php include $raiz . "/js/corejs.php"; ?>
<script src="<?php echo $link; ?>/js/jquery.form.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskedinput-1.1.4.pack.js" />
</script>
<script src="<?php echo $link; ?>/js/jquery.validate.js" />
</script>
<script src="<?php echo $link; ?>/js/jquery.inputmask.bundle.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskMoney.js" />
</script>
<script src="<?php echo $link; ?>/js/ckeditor/ckeditor.js"></script>


<script>
$(document).ready(function() {
    $('[rel=tooltip]').tooltip();
    $('[data-toggle=popover]').popover();
});
window.onload = function() {
    CKEDITOR.replace('OcorMensagem');
};

var filtro_contrato = "";
var filtro_evento = "";
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

var total_results = 0;

var order = "id";
var ordem = "desc";

var delay_busca;
$(function() {
    <?php 
        if (isset($ini_filtro) && $ini_filtro) {
            ?> filtrar_fields();
    <?php 
    } else {
        ?>carregar_resultados();
    <?php 
    } ?>
    carregar_totais();
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

$("#form_contratos").validate({
    rules: {
        descricao: {
            required: true
        },
        dt_contrato: {
            required: true
        },
        vl_contrato: {
            required: true
        },
        vendedor_id: {
            required: true
        },
        eventos_id: {
            required: true
        },
        comprador_id: {
            required: true
        },
        nu_parcelas: {
            required: true
        },
        vl_entrada: {
            required: true
        },
        tp_contrato: {
            required: true
        },
    },
    messages: {
        tp_contrato: "* Informe o tipo do contrato",
        descricao: "* Preencha a descricao",
        dt_contrato: "* Preencha a data do contrato",
        vl_contrato: "* Preencha o valor do contrato",
        vendedor_id: "* Selecione o vendedor",
        eventos_id: "* Selecione o evento",
        comprador_id: "* Selecione o comprador",
        nu_parcelas: "* Informe o número de parcelas",
        vl_entrada: "* Informe o valor da entrada",
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

function modal_zerar_parcelas_suspensao(id) {
    $('#md_cadastro_zera_parcelas_suspensao_tt').html('Zerar parcelas do contrato');

    $('html, body, #md_cadastro_zera_parcelas_suspensao , #md_edit_parcelas').animate({
        scrollTop: 0
    }, 'fast');
    $('#inputIdContrato').val(id);
    $('#md_cadastro_zera_parcelas_suspensao').modal('show');
}

function zerar_parcelas_suspensao() {

var id_contrato = $('#inputIdContrato').val();
var motivo_zerado = $("#selectMotivoZerado").val();
var observacao_zerado = $("#textareaObservacaoZerado").val();
var id_parcela = $("input#inputIdParcela").val();
var acao = '';
var pergunta = '';

if ($.isNumeric(id_parcela)) {
    acao = 'zerar_parcela_unica';
    pergunta = 'a parcela em aberto (atrasada / a vencer)';
} else {
    acao = 'zerar_parcelas';
    pergunta = 'as parcelas em aberto (atrasadas + a vencer) ';
}

if (motivo_zerado == '') {
    jAlert('Selecione o motivo!', 'Não foi possível salvar as alterações!', 'alert');
} else {
    jConfirm('Tem certeza que deseja zerar ' + pergunta + ' deste contrato ID: ' + id_contrato +
        '?<br>Após esta confirmação, as informações não poderão ser mais editadas!',
        'Zerar parcelas em aberto?',
        function(r) {
            if (r) {
                $.getJSON("<?php echo $link."/repositories/contratos/contratos.ctrl.php?";?>", {
                    acao: acao,
                    id_contrato: id_contrato,
                    motivo_zerado: motivo_zerado,
                    observacao_zerado: observacao_zerado,
                    id_parcela: id_parcela
                }, function(result) {
                    if (result.status == 1) {
                        $('#md_cadastro_zera_parcelas_suspensao').modal('hide');
                        $('#md_edit_parcelas').modal('hide');
                        jAlert(result.msg, 'Bom trabalho!', 'ok');
                        filtrar_fields();
                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                    }
                });
            } else {
                jAlert('As informações estão seguras.', 'Ação Cancelada!', 'ok');
            }
        });
}
}

function informa_motivo_suspensao() {
    event.preventDefault();
    $.getJSON(
        "<?php echo $link . "/repositories/alertas/alertas.ctrl.php?acao=inserir_alerta"; ?>", {
            contrato: contrato,
            pessoas_id:4,
            motivoSuspensao: $('#motivoSuspensao').val()
        },
        function(result) {
            if (result.status > 0) {
                jAlert(result.msg, 'A sua solicitação foi encaminhada. Aguarde a resposta do setor responsável!', 'ok');
            } else {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            }
            $('#mdMotivoSuspensao').modal('hide');
            document.getElementById("cont_exibidos").value = 0;
            carregar_resultados();
    });
}

function alimenta_modal_edit_descricao(id, contratos) {

    $('#md_cadastro_edit_desc_tt').html('Editar descricação do contrato: ' + contratos.descricao);
    $("input#inputIdEditDesc").val(id);
    $("input#inputEditDescricao").val('');

    $('#md_cadastro_edit_desc').modal('show');
}

function salvarEditDesc() {
    id = $("input#inputIdEditDesc").val();
    descricao = $("input#inputEditDescricao").val();

    jConfirm('Alterar a descrição do contrato para: ' + descricao, 'Confirmar?', function(r) {
        if (r) {
            $.getJSON(
            "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=edit_descricao"; ?>", {
                contratos_id: id,
                descricao: descricao
            }, function(result) {
                if (result) {
                    jAlert('Editado com sucesso!', 'Bom trabalho!', 'ok');
                    document.getElementById("cont_exibidos").value = 0;
                    carregar_resultados();
                    $('#md_cadastro_edit_desc').modal('hide');
                } else {
                    jAlert('Não foi possível editar este contrato!', 'Alerta', 'alert');
                }
            });


        } else {
            jAlert('As informções estão seguras.', 'Ação cancelada!');
            $('#md_cadastro_edit_desc').modal('show');
        }
    });
}


function alimenta_modal_cad_contratos(id, contratos, tipo, copia) {

    $('#remove_boletos').addClass('hidden');
    $('#input_remove_boletos').val('');
    $('#bt_gerar_boletos').addClass('hidden');
    $("input#inputGerarBoleto").attr('disabled', false);


    $('.control_edit_contrato_div').removeClass('hidden');
    $('.fa-refresh').removeClass('hidden');
    $('.control_edit_contrato_input').attr("disabled", false);


    limpa_evento();
    limpa_pessoa(0);
    if (id == 0) {
        $('#md_cadastro_contratos_tt').html('Cadastro de novo contrato');
        $("input#inputId").val('');
        $("input#inputStatus").val('pendente');
        $("input#inputDescricao").val('');
        $("input#inputDtContrato").val('');
        $("#selectParcelas").val('');
        $("input#inputVlContrato").val('');
        $("input#inputVlEntrada").val('');
        $("input#inputNuParcPagto").val('');
        $('#TpContrato').val(tipo);
        $("input#inputGerarBoleto").attr('checked', true);
    } else {

        $('#md_cadastro_contratos_tt').html('Editar Cadastro do contrato ' + contratos.descricao);
        $("input#inputId").val(id);
        $("input#inputStatus").val(contratos.status);
        $("input#inputDescricao").val(contratos.descricao);
        $("input#inputDtContrato").val(ConverteData(contratos.dt_contrato));
        $("#selectParcelas").val(contratos.nu_parcelas);
        $('#TpContrato').val(contratos.tp_contrato);
        $("input#inputVlContrato").val(number_format(contratos.vl_contrato, 2, '.', ''));
        $("input#inputVlEntrada").val(number_format(contratos.vl_entrada, 2, '.', ''));
        $("input#inputNuParcPagto").val(contratos.parcela_primeiro_pagto);

        $("input#inputGerarBoleto").attr('checked', false);
        if (contratos.gerar_boleto == "S") {
            $("input#inputGerarBoleto").attr('checked', true);
            if ($.isNumeric(contratos.arquivo_id)) {
                $('#remove_boletos').removeClass('hidden');
                $('#input_remove_boletos').val(id);
            }
        }

        if ($.isNumeric(contratos.comprador_id)) {
            comprador = {
                'id': contratos.comprador_id,
                'nome': contratos.comprador_nome,
                'email': contratos.comprador_email,
                'foto': contratos.comprador_foto,
                'cpf_cnpj': contratos.comprador_cpf_cnpj
            };
            escolhe_autocomplete_pessoa(comprador, 'compradores');
        }

        if ($.isNumeric(contratos.vendedor_id)) {
            vendedor = {
                'id': contratos.vendedor_id,
                'nome': contratos.vendedor_nome,
                'email': contratos.vendedor_email,
                'foto': contratos.vendedor_foto,
                'cpf_cnpj': contratos.vendedor_cpf_cnpj,
                'honor_adimp': contratos.honor_adimp,
                'honor_inadimp': contratos.honor_inadimp
            };
            escolhe_autocomplete_pessoa(vendedor, 'vendedores');
        }
        if ($.isNumeric(contratos.eventos_id)) {
            if (contratos.eventos_id == 1) {
                venda_direta();
            } else {
                evento = {
                    'id': contratos.eventos_id,
                    'nome': contratos.evento_nome,
                    'leiloeiro_nome': contratos.leiloeiro_nome,
                    'tipo': contratos.evento_tipo
                };
                escolhe_autocomplete_evento(evento);
            }
        }

        // se o contrato não estiver 'pendente' , não permite atualizar informações
        if (contratos.contratos_id_pai != null || contratos.status != 'pendente') {
            $('.control_edit_contrato_div').addClass('hidden');
            $('.fa-refresh').addClass('hidden');
            $('.control_edit_contrato_input').attr("disabled", true);

            if (($.isNumeric(contratos.arquivo_id)) || (contratos.pc_total == contratos.pc_liqd)) {
                $("input#inputGerarBoleto").attr('disabled', true);
            }

        }

    }

    if (copia == 1) {
        $('#md_cadastro_contratos_tt').html('COPIANDO Cadastro do contrato ' + contratos.descricao);
        $("input#inputId").val('');
        $('.control_edit_contrato_input').attr("disabled", false);
        $('.control_edit_contrato_div').removeClass('hidden');
        $("input#inputGerarBoleto").attr('disabled', false);
        $("input#inputGerarBoleto").attr('checked', true);
        $('#remove_boletos').addClass('hidden');
        $('#input_remove_boletos').val('');
        $("input#inputStatus").val('pendente');
    }

    $('#md_cadastro_contratos').modal('show');
}


function salvarFormulario() {
    $('#btn-save-contrato').addClass('hidden');
    id = $("input#inputId").val();
    if (id.length == 0) {
        acao = 'inserir';
    } else {
        acao = 'atualizar';
    }
    contratos = $('#form_contratos').serializeArray();
    //alert(JSON.stringify(contratos));
    $.getJSON("<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao="; ?>" + acao, {
        contratos: contratos
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

            carregar_totais();

            $('#md_cadastro_contratos').modal('hide');

            if (result.status == '9') {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            } else {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            }
        } else {
            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
        }
        $('#btn-save-contrato').removeClass('hidden');
    });
}

<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

function limpa_filtros(contrato_id) {
    $('#filtro_evento').val('');
    $('#filtro_status').val('');
    $('#filtro_pagto').val('');
    $('#filtro_data').val('');
    $('#filtro_data_fim').val('');
    $('#filtro_dia').val('');
    $('#filtro_vendedor').val('');
    $('#filtro_comprador').val('');
    $('#filtro_id').val('');
    $('#filtro_zerado').val('');

    filtrar = 0;

    if (contrato_id) {
        $('#filtro_id').val(contrato_id);
    }

    filtrar_fields();
}
$('#filtro_contrato').val('');

function filtrar_fields() {
    filtro_evento = $('#filtro_evento').val();
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

    $('#tbody_contratos').html('<tr><td colspan="10">Carregando contratos</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

    carregar_totais();
    carregar_resultados();
}

function filtra_contrato_relacionado(contrato_id) {
    limpa_filtros(contrato_id);
}


function carregar_totais() {

    $('#linha_totais').html('');
    $.getJSON('<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=listar_totais"; ?>', {
        filtro_contrato: filtro_contrato,
        filtro_data: filtro_data,
        filtro_data_fim: filtro_data_fim,
        filtro_status: filtro_status,
        filtro_pagto: filtro_pagto,
        filtro_vendedor: filtro_vendedor,
        filtro_comprador: filtro_comprador,
        filtrar: filtrar,
        filtro_id: filtro_id,
        filtro_zerado: filtro_zerado,
        filtro_dia: filtro_dia,
        filtro_evento: filtro_evento,
        ajax: 'true'
    }, function(j) {
        $('#linha_totais').html('Encontrados ' + j['qtd'] + ' contratos');
        total_results = j['qtd'];
    });


}

function carregar_resultados(open_parcelas) {
    //quantos já foram exibidos e descartar ids exibidos na cidade principal
    exibidos = document.getElementById("cont_exibidos").value;
    if (exibidos == 0) {
        nova_listagem = 1;
    } else {
        nova_listagem = 0;
    }

    document.getElementById("loading_resultados").style.display = 'block';
    libera_carregamento = 0;
    $.getJSON('<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=lista_contratos"; ?>&inicial=' +
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
            filtro_evento: filtro_evento,
            order: order,
            ordem: ordem,
            filtrar: filtrar,
            filtro_dia: filtro_dia,
            ajax: 'true'
        },
        function(j) {
            cont_novos = 0;
            novos = "";
            //alert(JSON.stringify(j));
            for (var i = 0; i < j.length; i++) {
                exibidos++;
                cont_novos++;

                //open tr
                contratos_aux = JSON.stringify(j[i]);


                if (open_parcelas == j[i].id) {
                    contrato_parcelas = j[i];
                }

                if (j[i].tp_contrato == 'adimplencia') {
                    fundo_tr = '#effce0';
                    tooltip_tr = 'Contrato de adimplência';
                } else if (j[i].tp_contrato == 'inadimplencia') {
                    fundo_tr = '#fff2f2';
                    tooltip_tr = 'Contrato de inadimplência';
                }

                if (j[i].suspenso == 'S') {
                    fundo_tr = '#f2f2f2';
                    tooltip_tr = 'Contrato suspenso';
                }

                novos += '<tr style="background-color:' + fundo_tr + '" id="tr_' + j[i].id + '">';

                //td #
                novos += '<td class="hidden-xs hidden-sm">';
                novos += j[i].id;
                novos += '</td>';

                //td codigo produto
                novos += '<td>';
                <?php if (consultaPermissao($ck_mksist_permissao, "cad_contratos", "editar")) { ?>
                novos +=
                    "<a><span class='pointer  fl-rg' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_edit_descricao(" +
                    j[i].id + "," + contratos_aux +
                    " )'; > <i class='fa fa-refresh fs-14 fl-rg' > </i></span> </a>";
                <?php 
                } ?>

                novos += j[i].descricao;
                novos += "<br><a href='<?php echo $link; ?>/pessoa/" + j[i].pessoas_id_inclusao +
                    "' target='_blank'>Cadastrado por: id " + j[i].pessoas_id_inclusao + "</a>";

                novos += '<span class="visible-xs visible-sm">';
                if (j[i].dt_contrato != null) novos += ConverteData(j[i].dt_contrato) + '<br>';
                novos += 'Vendedor:<br>' + j[i].vendedor_nome + '<br>';
                novos += 'Comprador:<br>' + j[i].comprador_nome + '<br>';
                novos += 'Evento:<br>' + j[i].evento_nome + '<br>';
                novos += '</span>';



                novos += '</td>';

                //td data 
                novos += '<td class="hidden-xs hidden-sm">';
                if (j[i].dt_contrato != null) novos += ConverteData(j[i].dt_contrato);
                novos += '</td>';

                //td valor 
                novos += '<td  class="nowrap">';
                if (j[i].vl_contrato != null) novos += 'R$ ' + number_format(j[i].vl_contrato, 2);
                novos += '</td>';

                //td evento 
                novos += '<td class="hidden-xs hidden-sm">';
                novos += j[i].evento_nome;
                novos += '</td>';

                //td vendedor 
                novos += '<td class="hidden-xs hidden-sm">';
                novos += j[i].vendedor_nome;
                novos += '</td>';

                //td comprador 
                novos += '<td class="hidden-xs hidden-sm">';
                if (j[i].comprador_eh_vendedor == 'S'){
                    novos += `<span title="Este comprador também é um vendedor" class="orange">${j[i].comprador_nome}</span>`;
                    novos += '<i title="Este comprador também é um vendedor" class="fa fa-star orange"></i>';
                } else {
                    novos += `<span class="">${j[i].comprador_nome}</span>`;
                }
                novos += '</td>';

                //td status 
                novos += '<td class="hidden-xs hidden-sm">';
                if (j[i].suspenso == 'S') {
                    novos += "Suspenso (<strike>" + j[i].status + "</strike>)";

                } else if (j[i].status == 'excluido') {
                    novos += 'Excluído';                
                } else {
                    if (j[i].repasse == 'S') {
                        novos += 'Repasse ('+j[i].status+')';
                    } else {
                        novos += j[i].status;
                    }
                    // if (j[i].repasse != 'S') {
                        if (j[i].motivo_zerado != null) {
                            novos += " <br> " + j[i].motivo_zerado;
                        }

                        situacao_parcelas = "";
                        if ($.isNumeric(j[i].pc_atrasada) && j[i].pc_atrasada > 0) {
                            situacao_parcelas = "Em atraso";
                            situacao_color = "red_light";
                        } else if (j[i].pc_total == j[i].pc_liqd) {
                            situacao_parcelas = "Liquidado";
                            situacao_color = "green_light";
                        } else {
                            situacao_parcelas = "A vencer";
                            situacao_color = "blue_light";
                        }
                        situacao_parcelas += " (" + j[i].pc_liqd + "/" + j[i].pc_total + ")";
                        if ((j[i].status != "em_acordo") && (j[i].status != "virou_inadimplente") && (j[i].status !=
                                "acao_judicial")) {
                            novos += "<div class='" + situacao_color + "'>" + situacao_parcelas + "</div>";
                        }
                    // }
                }
                novos += '</td>';

                //td acao
                novos += "<td class='nowrap'>";
                if (j[i].status != 'excluido'){
                    <?php if (consultaPermissao($ck_mksist_permissao, "cad_contratos", "editar")) { ?>

                    novos +=
                        " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_contratos(" +
                        j[i].id + "," + contratos_aux +
                        " )'; > <i class='fa fa-pencil-square-o fs-19 ' > </i></span> </a>";

                    if (j[i].contratos_id_pai == null) {
                        novos +=
                            " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Copiar Contrato' data-original-title='Editar' onClick='alimenta_modal_cad_contratos(" +
                            j[i].id + "," + contratos_aux +
                            ",0,1 )'; > <i class='fa fa-copy fs-19 ' > </i></span> </a>";
                    }
                    novos +=
                        " <a><span class='pointer mg-lf-5' data-toggle='tooltip' data-placement='left' title='Editar parcelas' data-original-title='Editar parcelas' onClick='get_parcelas(" +
                        j[i].id + "," + contratos_aux +
                        " )'; > <i class='fa fa-list-ol fs-19 orange' aria-hidden='true'></i></span> </a>     ";

                    novos +=
                        " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Documentos' data-original-title='Documentos' onClick='alimenta_modal_documentos(" +
                        j[i].id + " )'; > <i class='fa fa-file-o fs-19  blue_light' > </i></span> </a>";

                    novos +=
                        " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ocorrências' data-original-title='Ocorrências' onClick='alimenta_modal_ocorrencias(" +
                        j[i].id + " )'; > <i class='fa fa fa-comments fs-19 blue_light' > </i></span> </a>";



                    //novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Gerar Boletos' data-original-title='Gerar Boletos' onClick='gerar_boletos("+j[i].id+" )'; > <i class='fa fa-usd fs-19' > </i></span> </a>";

                    //if(<?php echo $is_admin; ?> || j[i].status == "pendente"){
                    if ((<?php echo $_SESSION['perfil_id'] ?> == 1 || <?php echo $_SESSION['perfil_id'] ?> == 3) && j[i].contrato_filho == null) { // j[i].contratos_id_pai == null &&
                        novos +=
                            " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Apagar Contrato ' data-original-title='Apagar Contrato ' onClick='remover_contrato(" +
                            j[i].id + " )'; > <i class='fa fa-trash-o  fs-19 red' ></i></span> </a>";
                    }
                    //}

                    <?php 
                    } ?>

                    if (j[i].contratos_id_pai != null && $.isNumeric(j[i].contratos_id_pai)) {
                        novos +=
                            " <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ver Contrato Original' data-original-title='Ver Contrato Original' onClick='filtra_contrato_relacionado(" +
                            j[i].contratos_id_pai + " )'; > <i class='fa fa-star fs-19 green_light' > </i></span> </a>";
                    }
                    if (j[i].contrato_filho != null && $.isNumeric(j[i].contrato_filho)) {
                        novos +=
                            "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ver Novo Contrato ' data-original-title='Ver Novo Contrato ' onClick='filtra_contrato_relacionado(" +
                            j[i].contrato_filho + " )'; > <i class='fa fa-star-o  fs-19 green_light' ></i></span> </a>";
                    }

                    if (j[i].tp_contrato == 'adimplencia' && j[i].tt_inadp != null && j[i].tt_inadp > 0) {
                        novos +=
                            " <span   data-toggle='tooltip' data-placement='left' title='Parcela vencida a mais de 5 dias' data-original-title='Parcela vencida a mais de 5 dias'  > <i class='fa fa-exclamation-triangle red_light' aria-hidden='true'></i></span> ";
                    }

                    if (((jQuery.inArray(<?php echo $_SESSION['perfil_id']; ?>, [1, 3] ) >= 0) || "<?php echo $_SESSION['supervisor']; ?>" == "S") && (j[i].status == "confirmado" || j[i].status == "pendente")) {
                        supervisor = "<?php echo $_SESSION['supervisor']; ?>";
                        if (j[i].suspenso != 'S') { // j[i].contratos_id_pai == null &&
                            novos += " <a href='#' title='Suspender' data-contrato='" + j[i].id +
                                "' class='suspender_contrato' data-supervisor='"+supervisor+"'><i class='fa fa-pause  fs-19 red' ></i></a>";
                        } else if (((jQuery.inArray(<?php echo $_SESSION['perfil_id']; ?>, [1, 3] ) >= 0)) && (j[i].status == "confirmado" || j[i].status == "pendente")) {
                            novos += " <a href='#' title='Remover suspensão' data-contrato='" + j[i].id +
                                "' class='remover_suspensao_contrato'><i class='fa fa-play  fs-19 red' ></i></a>";
                        }
                    }
                    if (<?php echo $_SESSION['perfil_id'] ?> == 1 || <?php echo $_SESSION['perfil_id'] ?> == 3) {
                        if (j[i].repasse != "S") {
                            novos += " <a href='#' title='Transformar em repasse' data-contrato='" + j[i].id +
                                "' class='contrato_repasse'><i class='fa fa-share red_light' aria-hidden='true'></i></span> ";
                        } else {
                            novos += " <a href='#' title='Revogar de repasse' data-contrato='" + j[i].id +
                                "' class='remover_contrato_repasse'><i class='fa fa-share red_light' aria-hidden='true' style='transform:rotate(180deg)' ></i></span> ";
                        }
                        
                    }

                    if (j[i].tp_contrato == 'adimplencia' && $.isNumeric(j[i].contratos_id_pai)) {
                        novos += " <a href='#' title='Desfazer acordo' data-contrato='" + j[i].id +
                            "' data-contrato_pai='" + j[i].contratos_id_pai +
                            "' class='desfazer_acordo'><i class='fa fa-thumbs-down aria-hidden='true'></i></span> ";
                    }

                    novos += "</td>";
                }
                novos += '</tr>';

            }
            if (exibidos == 0) {
                novos = "<tr><td colspan='10'>Nenhum contrato cadastrado</td></tr>";
            }
            //Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
            if (cont_novos == 30) {
                libera_carregamento = 1;
            }

            if (nova_listagem == 1) {
                $('#tbody_contratos').html(novos);
            } else {
                $('#listagem_contratos').append(novos);
            }
            document.getElementById("loading_resultados").style.display = 'none';
            document.getElementById("cont_exibidos").value = exibidos;
            document.getElementById("permite_carregar").value = libera_carregamento;
            if (open_parcelas) {
                get_parcelas(open_parcelas, contrato_parcelas, true);
            }
        });
}

$("#listagem_contratos").on('click', '.suspender_contrato', function() {
    event.preventDefault;
    contrato = $(this).data('contrato');
    jConfirm(`Suspendendo o contrato, todas as cobranças referente a este serão suspensas, até o mesmo ser reativado.
						Deseja suspender o contrato?`, 'Suspender contrato ' + contrato + '?', function(r) {
        if (r) {
            if ($('.suspender_contrato').data('supervisor') == 'S'){
                $('#mdMotivoSuspensao').modal('show');
            } else {

            
                $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=suspenderContrato"; ?>", {
                        contrato: contrato
                    },
                    function(result) {
                        if (result.status > 0) {
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                        }
                        document.getElementById("cont_exibidos").value = 0;
                        carregar_resultados();
                });
            }
        };
    });
});

$("#listagem_contratos").on('click', '.remover_suspensao_contrato', function() {
    event.preventDefault;
    contrato = $(this).data('contrato');
    jConfirm(`Deseja reativar esta contrato?`, 'Remover suspensão do contrato ' + contrato + '?', function(r) {
        if (r) {
            jYesNo(`Deseja realocar as parcelas vencidas durante a suspensão, para o final do contrato ?`,'Realocar parcelas vencidas do contrato ' + contrato + '?',function(r) {
                if (r) {
                    var realocar = "sim";
                } else {
                    var realocar = "nao";
                }
                if (realocar == 'nao') {
                    jYesNo(`Deseja cancelar o contrato? Caso clique em "Não", o contrato retornará ao estado original, anterior a suspensão?`,'Cancelar contrato ' + contrato + '?',function(r2) {
                        if (r2) {
                            var cancelar = "sim";
                        } else {
                            var cancelar = "nao";
                        }
                        $.getJSON(
                        "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=removerSuspensaoContrato&realocar="; ?>" +
                        realocar+"&cancelar="+cancelar, {
                            contrato: contrato
                        },
                        function(result) {
                            if (result.status > 0) {
                                modal_zerar_parcelas_suspensao(contrato);
                            } else {
                                jAlert(result.msg, 'Não foi possível salvar as alterações!',
                                    'alert');
                            }
                            document.getElementById("cont_exibidos").value = 0;
                            carregar_resultados();
                        });
                        
                    });
                } else if ( realocar == 'sim' ) {
                    $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=removerSuspensaoContrato&realocar="; ?>" +
                    realocar, {
                        contrato: contrato
                    },
                    function(result) {
                        if (result.status > 0) {
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!',
                                'alert');
                        }
                        document.getElementById("cont_exibidos").value = 0;
                        carregar_resultados();
                    });
                };
            });
        };
    });
});

$("#listagem_contratos").on('click', '.contrato_repasse', function() {
    event.preventDefault;
    contrato = $(this).data('contrato');
    jConfirm(`Transformar em contrato de repasse?`, 'Transformar ' + contrato + '?', function(r) {
        if (r) {
            $.getJSON(
                "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=contratoRepasse"; ?>", {
                    contrato: contrato
                },
                function(result) {
                    if (result.status > 0) {
                        jAlert(result.msg, 'Bom trabalho!', 'ok');
                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                    }
                    document.getElementById("cont_exibidos").value = 0;
                    carregar_resultados();
                });
        };
    });
});

$("#listagem_contratos").on('click', '.remover_contrato_repasse', function() {
    event.preventDefault;
    contrato = $(this).data('contrato');
    jConfirm(`Retirar o contrato como repasse?`, 'Revogar repasse ' + contrato + '?', function(r) {
        if (r) {
            $.getJSON(
                "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=removerContratoRepasse"; ?>", {
                    contrato: contrato
                },
                function(result) {
                    if (result.status > 0) {
                        jAlert(result.msg, 'Bom trabalho!', 'ok');
                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                    }
                    document.getElementById("cont_exibidos").value = 0;
                    carregar_resultados();
                });
        };
    });
});

$("#listagem_contratos").on('click', '.desfazer_acordo', function() {
    event.preventDefault;
    contrato = $(this).data('contrato');
    contrato_pai = $(this).data('contrato_pai');
    jConfirm(`Tem certeza que deseja desfazer este acordo, e retornar as parcelas do contrato original?`,
        'Desfazer acordo ' + contrato + '?',
        function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=desfazerAcordo"; ?>", {
                        contrato_pai: contrato_pai,
                        contrato: contrato
                    },
                    function(result) {
                        if (result.status > 0) {
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                        }
                        document.getElementById("cont_exibidos").value = 0;
                        carregar_resultados();
                    });
            };
        });
});

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

function gerar_planilha_lista() {
    if (total_results > 5000) {
        jAlert('Você está tentando gerar um pdf de ' + total_results +
            ' registros.<br>Para não prejudicar seu processo limite a sua consulta a até 5.000 registros.', 'Oops');
    } else {
        direct = '<?php echo $link . "/adm/contratos/gera_planilha_contratos.php"; ?>?order=' + order + '&ordem=' +
            ordem;
        $('#form_filtros_contratos').attr('action', direct);
        $('#form_filtros_contratos').attr('target', '_blank');
        $('#form_filtros_contratos').submit();
        $('#form_filtros_contratos').attr('action', 'javascript:filtrar_fields();');
        $('#form_filtros_contratos').attr('target', '_top');
    }
}

function gerar_planilha_vendedores() {
    direct = '<?php echo $link . "/adm/contratos/gera_planilha_vendedores.php"; ?>'
    $('#form_filtros_contratos').attr('action', direct);
    $('#form_filtros_contratos').attr('target', '_blank');
    $('#form_filtros_contratos').submit();
    $('#form_filtros_contratos').attr('action', 'javascript:filtrar_fields();');
    $('#form_filtros_contratos').attr('target', '_top');
}

function gerar_pdf_lista() {
    if (total_results > 10000) {
        jAlert('Você está tentando gerar um pdf de ' + total_results +
            ' registros.<br>Para não prejudicar seu processo limite a sua consulta a até 10.000 registros.', 'Oops');
    } else {
        $('#form_filtros_contratos').attr('action',
            '<?php echo $link . "/inc/pdf/gera_pdf_listas.php?pagina=contratos"; ?>&order=' + order + '&ordem=' +
            ordem
        );
        $('#form_filtros_contratos').attr('target', '_blank');
        $('#form_filtros_contratos').submit();
        $('#form_filtros_contratos').attr('action', 'javascript:filtrar_fields();');
        $('#form_filtros_contratos').attr('target', '_top');
    }
}

<!-- INICIO DOCUMENTOS	  -->

function md_termo_divida() {
    $('#md_documentos').modal('hide');
    $('#mdTermoDivida_bd_info').html();
    $('#mdTermoDivida').modal('show');
}

function salvar_termodivida_world() {
    contratos = $('#form_termoDivida').serializeArray();
    contrato_id = $('#TermoContratoId').val();
    //alert(JSON.stringify(contratos));
    $.getJSON("<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=atualizarTermo"; ?>", {
        contratos: contratos
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
            cancela_termoDivida();
            jAlert('Termo Gerado com sucesso', 'Bom trabalho!', 'ok');
            botao_novo = '<a href="<?php echo $link . "/inc/pdf/gera_pdf_termo.php?word=s&id="; ?>' + contrato_id +
                '" target="_blank"  onClick="reset_btn_termo();"  ><input type="button" class="btn mg-tp-15  btn-success" value="Baixar .doc" id="popup_termo"></a>';

            $('#popup_ok').addClass('hidden');
            $('#popup_panel').append(botao_novo);


        } else {
            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
        }
    });
}

function salvar_termodivida() {
    contratos = $('#form_termoDivida').serializeArray();
    contrato_id = $('#TermoContratoId').val();
    //alert(JSON.stringify(contratos));
    $.getJSON("<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=atualizarTermo"; ?>", {
        contratos: contratos
    }, function(result) {
        if (result.status > 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
            cancela_termoDivida();
            jAlert('Termo Gerado com sucesso', 'Bom trabalho!', 'ok');
            botao_novo = '<a href="<?php echo $link . "/inc/pdf/gera_pdf_termo.php?id="; ?>' + contrato_id +
                '" target="_blank"  onClick="reset_btn_termo();"  ><input type="button" class="btn mg-tp-15  btn-success" value="Visualizar PDF" id="popup_termo"></a>';

            $('#popup_ok').addClass('hidden');
            $('#popup_panel').append(botao_novo);


        } else {
            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
        }
    });
}

function cancela_termoDivida() {
    $('#form_termoDivida').trigger("reset");
    $('#mdTermoDivida_bd_info').html('');
    $('#mdTermoDivida').modal('hide');
    $('#md_documentos').modal('show');
}

function reset_btn_termo() {
    $('#popup_termo').addClass('hidden');
    setTimeout(function() {
        $('#popup_termo').remove();
        $('#popup_ok').removeClass('hidden');
        $('#popup_ok').click();

    }, 100);

}

function alimenta_modal_documentos(contrato_id) {
    $('#TermoContratoId').val(contrato_id);
    $.getJSON('<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=lista_documentos"; ?>', {
        contrato_id: contrato_id,
        ajax: 'true'
    }, function(j) {
        cont_novos = 0;
        novos = "";
        //alert(JSON.stringify(j));
        for (var i = 0; i < j.length; i++) {
            cont_novos++;
            //open tr
            documento_aux = JSON.stringify(j[i]);
            novos += '<tr id="tr_doc_' + j[i].id + '">';

            //td codigo produto
            novos += '<td>';
            novos += j[i].descricao;
            novos += '</td>';

            novos += '<td>';

            novos += "<a href='<?php echo $link . "/documentos/"; ?>" + j[i].file +
                "' target='_blank' ><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ver documento' data-original-title='Ver documento'  > <i class='fa fa-eye fs-19' > </i></span> </a>";

            <?php if (consultaPermissao($ck_mksist_permissao, "cad_contratos", "editar")) { ?>

            //btn_limpa_pessoa = '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_pessoa(&#39;'+tipo_pessoa+'&#39;);"></span>';

            novos += '<a onClick="remove_documento(' + j[i].id + ', &#39;' + j[i].descricao + '&#39;, &#39;' +
                j[i].file +
                '&#39; )" class="mg-lf-10"><span class="pointer" data-toggle="tooltip" data-placement="left" title="Excluir" data-original-title="Excluir"  > <i class="fa fa-trash fs-19" > </i></span> </a>';


            <?php 
            } ?>
            novos += "</td>";

            novos += '</tr>';
        }
        if (cont_novos == 0) {
            novos = "<tr><td colspan='10'>Nenhum documento a recuperar</td></tr>";
        }
        $('#inputDocContato').val(contrato_id);
        //Se a quantidade de resultados for igual ao total esperado, libera para carregar mais

        $('#tbody_docs').html(novos);
        $('#md_documentos').modal('show');

    });

}

function alimenta_modal_recalcular_boletos(parcela_id) {
    event.preventDefault();
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "get_parcela",
            parcela_id: parcela_id
        }
    }).done(function(data) {
        $('#md_boleto_recalculado').modal('show');
        organiza_dados_parcela(JSON.parse(data)[0]);
    });
}

function organiza_dados_parcela(dados_parcela) {
    var valor = dados_parcela.vl_parcela;
    var vencimento = new Date(dados_parcela.dt_vencimento);
    var hoje = new Date();
    var diferenca_tempo = Math.abs(hoje.getTime() - vencimento.getTime());
    var diferenca_dias = Math.ceil(diferenca_tempo / (1000 * 3600 * 24));
    $('#total_boleto_recalculado_total').html('R$ ' + valor);
    $('#md_boleto_recalculado').data('valor', valor);
    $('#md_boleto_recalculado').data('dias_atraso', diferenca_dias);
    $('#md_boleto_recalculado').data('vencimento_anterior', vencimento);

    $('#gerar_novo_boleto').data('contrato', dados_parcela.contratos_id);
    $('#gerar_novo_boleto').data('parcela', dados_parcela.id);
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "calcula_correcao",
            vencimento_antigo: vencimento.getFullYear() + '-' + vencimento.getMonth() + '-' + vencimento
                .getDate(),
            vencimento_novo: hoje.getFullYear() + '-' + hoje.getMonth() + '-' + hoje.getDate(),
            valor: valor
        }
    }).done(function(data) {
        $('#md_boleto_recalculado').data('correcao', data);
        var day = ("0" + hoje.getDate()).slice(-2);
        var month = ("0" + (hoje.getMonth() + 1)).slice(-2);
        var year = hoje.getFullYear();
        $('#vencimento_boleto_recalculado').val(year + '-' + month + '-' + day);
        calcula_juros_multa();
    });
}

function calcula_juros_multa() {
    var vencimento = new Date($('#md_boleto_recalculado').data('vencimento_anterior'));
    var atual = new Date($('#vencimento_boleto_recalculado').val());
    var diferenca_tempo = Math.abs(atual.getTime() - vencimento.getTime());
    var diferenca_dias = Math.ceil(diferenca_tempo / (1000 * 3600 * 24));
    $('#md_boleto_recalculado').data('dias_atraso', diferenca_dias);

    var valor = parseFloat($('#md_boleto_recalculado').data('valor'));
    var correcao = parseFloat($('#md_boleto_recalculado').data('correcao'));
    valor = valor + correcao;
    var dias_atraso = parseFloat($('#md_boleto_recalculado').data('dias_atraso'));

    var juros = parseFloat($('#juros_boleto_recalculado').val());
    juros = ((juros / 30) * dias_atraso) / 100;
    juros = (isNaN(juros / 2)) ? 0 : juros;
    juros = juros * valor;

    var multa_porcentagem = parseFloat($('#multa_boleto_recalculado_porcentagem').val());
    multa_porcentagem = (multa_porcentagem / 100);


    multa_porcentagem = (isNaN(multa_porcentagem)) ? 0 : multa_porcentagem;
    multa_porcentagem = (valor - correcao) * multa_porcentagem;

    var taxas = parseFloat($('#taxas_boleto_recalculado_reais').val());
    taxas = (isNaN(taxas / 2)) ? 0 : taxas;

    var honorarios = parseFloat($('#honorarios_boleto_recalculado_porcentagem').val());
    honorarios = (honorarios / 100);
    honorarios = (isNaN(honorarios / 2)) ? 0 : honorarios;

    var total_vencimento = $('#vencimento_boleto_recalculado').val();
    var total_recalculado = valor + juros + multa_porcentagem + taxas;
    var total_honorarios = Math.round((total_recalculado * honorarios) * 100) / 100;
    total_recalculado = Math.round((total_recalculado + total_honorarios) * 100) / 100;
    var total_original = Math.round((valor - correcao) * 100) / 100;
    var total_correcao = Math.round(correcao * 100) / 100;
    var total_juros = Math.round(juros * 100) / 100;
    var total_multa = Math.round(multa_porcentagem * 100) / 100;
    var total_taxas = Math.round(taxas * 100) / 100;

    $('#total_boleto_recalculado').val(total_recalculado);
    $('#total_boleto_recalculado_total').html('R$ ' + total_original);
    $('#total_boleto_recalculado_correcao').html('R$ ' + total_correcao);
    $('#total_boleto_recalculado_juros').html('R$ ' + total_juros);
    $('#total_boleto_recalculado_multa').html('R$ ' + total_multa);
    $('#total_boleto_recalculado_taxas').html('R$ ' + total_taxas);
    $('#total_boleto_recalculado_honorarios').html('R$ ' + total_honorarios);

    $('#gerar_novo_boleto').data('vencimento', total_vencimento);
    $('#gerar_novo_boleto').data('correcao', total_correcao);
    $('#gerar_novo_boleto').data('juros', total_juros);
    $('#gerar_novo_boleto').data('multa', total_multa);
    $('#gerar_novo_boleto').data('taxas', total_taxas);
    $('#gerar_novo_boleto').data('honorarios', total_honorarios);
    $('#gerar_novo_boleto').data('valor_corrigido', total_recalculado);


}

document.ready = function() {
    $("#md_boleto_recalculado").on('keyup', '#juros_boleto_recalculado', function() {
        calcula_juros_multa();
    });
    $("#md_boleto_recalculado").on('keyup', '#honorarios_boleto_recalculado_porcentagem', function() {
        calcula_juros_multa();
    });
    $("#md_boleto_recalculado").on('keyup', '#taxas_boleto_recalculado_reais', function() {
        calcula_juros_multa();
    });

    $("#md_boleto_recalculado").on('keyup', '#multa_boleto_recalculado_reais', function() {
        var valor = parseFloat($('#md_boleto_recalculado').data('valor'));
        var multa_porcentagem = parseFloat($(this).val());
        multa_porcentagem = (multa_porcentagem / valor);
        multa_porcentagem = (isNaN(multa_porcentagem / 2)) ? 0 : multa_porcentagem;

        $('#multa_boleto_recalculado_porcentagem').val(multa_porcentagem * 100);
        calcula_juros_multa();
    });
    $("#md_boleto_recalculado").on('keyup', '#multa_boleto_recalculado_porcentagem', function() {
        var valor = parseFloat($('#md_boleto_recalculado').data('valor'));
        var multa_reais = parseFloat($(this).val());
        multa_reais = (multa_reais / 100);
        multa_reais = (isNaN(multa_reais / 2)) ? 0 : multa_reais;

        $('#multa_boleto_recalculado_reais').val(valor * multa_reais);
        calcula_juros_multa();
    });
    $("#md_boleto_recalculado").on('change', '#vencimento_boleto_recalculado', function() {
        var vencimento = new Date($('#md_boleto_recalculado').data('vencimento_anterior'));
        var atual = new Date($('#vencimento_boleto_recalculado').val());

        var valor = parseFloat($('#md_boleto_recalculado').data('valor'));

        $.ajax({
            method: "POST",
            url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
            data: {
                acao: "calcula_correcao",
                vencimento_antigo: vencimento.getFullYear() + '-' + vencimento.getMonth() + '-' +
                    vencimento.getDate(),
                vencimento_novo: atual.getFullYear() + '-' + atual.getMonth() + '-' + atual
                .getDate(),
                valor: valor
            }
        }).done(function(data) {
            $('#md_boleto_recalculado').data('correcao', data);
            calcula_juros_multa();
        });
    });
    $("#md_boleto_recalculado").on('click', '#gerar_novo_boleto', function() {
        $.ajax({
            method: "POST",
            url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
            data: {
                acao: "gera_remessa_parcela",
                contratos_id: $(this).data('contrato'),
                parcelas_id: $(this).data('parcela'),
                vencimento: $(this).data('vencimento'),
                correcao: $(this).data('correcao'),
                juros: $(this).data('juros'),
                multa: $(this).data('multa'),
                taxas: $(this).data('taxas'),
                honorarios: $(this).data('honorarios'),
                valor_corrigido: $(this).data('valor_corrigido'),

            }
        }).done(function(data) {
            data = JSON.parse(data);
            if (data == 'ok')
                jAlert('Boleto e arquivo de remessa gerados', 'Bom trabalho!', 'ok');
            else
                jAlert('Ocorreu um erro, não foi possível salvar os dadoss', 'Erro', 'alert');
            $('#md_boleto_recalculado').modal('hide');
            $('#md_edit_parcelas').modal('hide');
            carregar_resultados();
        });
    });
};

function remover_contrato(contrato) {
    jConfirm(
        'Tem certeza que deseja remover este contrato e seus originais se possuir?<br>Esta informação não poderá ser recuperada!',
        'Excluir Contrato ' + contrato + '?',
        function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=remove_contratos"; ?>", {
                        contratos_id: contrato
                    },
                    function(result) {
                        if (result.status == 1) {
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                            document.getElementById("cont_exibidos").value = 0;
                            carregar_resultados();
                        } else {
                            jAlert(result.status + ' | ' + result.msg, 'Alerta', 'alert');
                        }
                    });


            }
        });

}

function remove_documento(id, descricao, file) {

    jConfirm('O arquivo "' + descricao + '" será removido e não poderá ser recuperado. Confirma exclusão?',
        'Remover Documento?',
        function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=remove_documento"; ?>", {
                        id: id,
                        file: file
                    },
                    function(result) {
                        if (result) {
                            $("#tr_doc_" + id).remove();
                            jAlert('Removido com Sucesso.', 'Bom trabalho!', 'ok');
                        } else {
                            jAlert('Não foi possível remover!', 'Oops', 'alert');
                        }
                    });
            } else {
                jAlert('O arquivo está seguro!', 'Ação Cancelada', 'ok');
            }
        });
}


(function() {
    var current_upload = current_percent = current_total = 0;
    $("#form_documento").ajaxForm({
        beforeSend: function() {
            var fileInput = document.getElementById("inputDoc");
            if (fileInput.files[0].size > 2097152) {
                atual = (fileInput.files[0].size / 1048576).toFixed(2);
                jAlert("Arquivos de no máximo 2 MB!\nTamanho da imagem: " + atual + " MB", 'Alerta');
            } else {
                var re = /(?:\.([^.]+))?$/;
                var ext = re.exec(document.getElementById("inputDoc").value)[1];
                ext = ext.toLowerCase();
                if (ext != 'jpg' && ext != 'jpeg' && ext != 'png' &&
                    ext != 'doc' && ext != 'docx' && ext != 'xls' && ext != 'pdf' && ext != 'txt'
                ) {
                    jAlert("Favor enviar arquivos no formato: JPG, PNG, DOC, XLS, TXT ou PDF");
                } else {
                    $('.uploading_doc').removeClass('hidden');
                }
            }
        },
        uploadProgress: function(event, position, total, percentComplete) {

            mb_pos = (position / 1048576).toFixed(2);
            mb_tot = (total / 1048576).toFixed(2);
            if (percentComplete > current_percent) {
                current_percent = percentComplete;
            }
            if (mb_pos > current_upload) {
                current_upload = mb_pos;
            }
            if (mb_tot > current_total) {
                current_total = mb_tot;
            }
            $("#bar_up_foto").html(current_percent + "%");
            $("#bar_up_foto").css("width", percentComplete + "%");
            $("#kb_upado").html(current_upload + " / " + current_total + " MB");
        },
        success: function() {

        },
        complete: function(xhr) {
            alimenta_modal_documentos($('#inputDocContato').val());
            $('.uploading_doc').addClass('hidden');
            $('#inputDocDesc').val('');
            $('#inputDoc').val('');
        }
    });
})();


<!-- INICIO OCORRENCIAS	  -->

function alimenta_modal_ocorrencias(contrato_id) {

    $.getJSON(
        '<?php echo $link . "/repositories/ocorrencias/ocorrencias.ctrl.php?acao=lista_ocorrencias"; ?>&inicial=' +
        exibidos, {
            contrato_id: contrato_id,
            ajax: 'true'
        },
        function(j) {
            vendedor = j.vendedor;

            /////    HEADER COMPRADOR E OUTRAS DÍVIDAS 

            //DADOS DO COMPRADOR
            comprador = j.comprador;
            //alert(JSON.stringify(comprador));
            html_header = '<div class="row">';
            html_header += '<div class="col-sm-2">';
            html_header += '<img id="foto_usr" src="<?php echo $link; ?>/imagens/fotos/nail/' + comprador.foto +
                '" alt="" class="img-responsive wd-100p">';
            html_header += '</div>';
            html_header += '<div class="col-sm-5 fs-13">';
            html_header += '<h3>Comprador</h3>';
            if (comprador.cpf_cnpj != null) html_header += comprador.nome + '<br>';
            if (comprador.cpf_cnpj != null) html_header += comprador.cpf_cnpj + '<br>';
            if (comprador.telefone != null) html_header += comprador.telefone + '<br>';
            if (comprador.celular != null) html_header += comprador.celular + '<br>';
            if (comprador.email != null) html_header += comprador.email + '<br>';

            if (comprador.rua != null) html_header += comprador.rua + " ";
            if (comprador.numero != null) html_header += comprador.numero + " ";
            if (comprador.bairro != null) html_header += comprador.bairro + " ";
            if (comprador.cidade != null) html_header += comprador.cidade + " ";
            if (comprador.estado != null) html_header += comprador.estado + " ";
            if (comprador.cep != null) html_header += comprador.cep + " ";


            html_header += '</div>';


            // DÍVIDAS
            dividas = j.dividas;
            //alert(JSON.stringify(dividas));
            html_header += '<div class="col-xs-12 col-sm-5">';
            html_header += '<h4>Dívidas deste comprador</h4>';
            for (var i = 0; i < dividas.length; i++) {

                html_header += '<a href="<?php echo $link . "/contratos/"; ?>' + dividas[i].id +
                    '" target="_blank">';
                html_header += "<div class='fs-12'> Contrato " + dividas[i].id;
                html_header += " - " + dividas[i].descricao;
                html_header += " (" + dividas[i].status;
                html_header += ") : R$ " + number_format(dividas[i].total, 2, ',', '.') + "</div></a>";
            }
            if (i == 0) {
                html_header += "<h4>Nenhuma outra dívida</h4>";
            }
            html_header += '</div>';



            html_header += '</div>';
            $('#md_ocorrencias_tt').html(html_header);

            /////    OCORRÊNCIAS
            ocorrencias = j.ocorrencias;
            //alert(JSON.stringify(ocorrencias));
            html_ocorrencias = "";
            for (var i = 0; i < ocorrencias.length; i++) {

                html_ocorrencias += "<h4><div class='row'>";
                html_ocorrencias += "<div class='col-sm-6'>";
                if (ocorrencias[i].contratos_id_original) {
                    html_ocorrencias += "Contrato: " + ocorrencias[i].contratos_id_original + " - " + ocorrencias[i]
                        .descricao + "  " + ConverteData(ocorrencias[i].dt_contrato) + " - (" + ocorrencias[i]
                        .c_status + ")";
                    html_ocorrencias +=
                        "<br><small style='color:#f00'>Esta ocorrência pertencia a um acordo que foi revogado</small>";
                } else {
                    html_ocorrencias += "Contrato: " + ocorrencias[i].c_id + " - " + ocorrencias[i].descricao +
                        "  " + ConverteData(ocorrencias[i].dt_contrato) + " - (" + ocorrencias[i].c_status + ")";
                }
                html_ocorrencias += "</div>";
                html_ocorrencias += "<div class='col-sm-6 ar'>";
                html_ocorrencias += "Usuario: " + ocorrencias[i].nome;
                html_ocorrencias += "</div>";
                html_ocorrencias += "</div></h4>";
                html_ocorrencias += "<div class='ocor_msg'>";
                html_ocorrencias += ConverteData(ocorrencias[i].data_ocorrencia);
                if (ocorrencias[i].promessa_pagamento != null)
                    html_ocorrencias += "<br>Status: " + ocorrencias[i].o_status + ' ('+ocorrencias[i].promessa_pagamento+')';
                else
                    html_ocorrencias += "<br>Status: " + ocorrencias[i].o_status;

                if (ocorrencias[i].mensagem != null)
                    html_ocorrencias += "<br>" + ocorrencias[i].mensagem;
                html_ocorrencias += "</div>";
                html_ocorrencias += "<hr>";
            }
            if (html_ocorrencias == "") {
                html_ocorrencias = "<h4>Nenhuma ocorrência cadastrada</h4>";
            }

            $('#md_ocorrencias_bd').html(html_ocorrencias);

            // SHOW OCORRENCIAS
            $('#OcorContrato').val(contrato_id);
            $('#md_ocorrencias').modal('show');

        });


}


function salvar_ocorrencia() {
    msg = document.getElementById('OcorMensagem').value.replace(/(^\s+|\s+$)/g, ' ');
    if (0) {
        jAlert("Preencha a mensagem/observação.");
    } else {
        $('#btn-save-ocor').addClass('hidden');
        ocorrencia = $('#form_ocorrencia').serializeArray();
        //alert(JSON.stringify(contratos));
        $.getJSON("<?php echo $link . "/repositories/ocorrencias/ocorrencias.ctrl.php?acao=inserir"; ?>", {
            ocorrencias: ocorrencia
        }, function(result) {
            $('#promessaPagamento').val('');
            $('#promessaPagamentoDiv').addClass('hidden');
            

            if (result.status == 1) {
                //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
                cancela_ocorrencia();
                $('#btn-save-ocor').removeClass('hidden');
                alimenta_modal_ocorrencias($('#OcorContrato').val());
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            } else {
                $('#btn-save-ocor').removeClass('hidden');
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            }
        });
    }
}

function nova_ocorrencia() {
    $('#OcorStatus').val('');
    $('#OcorMensagem').val('');
    CKEDITOR.instances.OcorMensagem.setData('');
    $('#divAddOcor').removeClass('hidden');
    $('#btnAddOcor').addClass('hidden');

}

function cancela_ocorrencia() {
    $('#OcorStatus').val('');
    $('#OcorMensagem').val('');
    CKEDITOR.instances.OcorMensagem.setData('');
    $('#divAddOcor').addClass('hidden');
    $('#btnAddOcor').removeClass('hidden');

}

<!-- FIM OCORRENCIAS	  -->



<!-- PARCELAS -->
function get_parcelas(contrato_id, contrato, cadastro = 'false') {
    //busca parcelas deste pedido
    $.get("<?php echo $link . "/adm/contratos/form_edit_parcelas.php"; ?>", {
        acao: 'get_parcelas',
        contrato_id: contrato_id,
        cadastro: cadastro
    }, function(result) {
        if (result == '0') {
            alert('nenhuma parcela a recuperar');
        } else {
            //exibe resultados												
            $('#md_edit_parcelas_tt').html('Editando parcelas do pedido ' + contrato_id);

            tp_contrato = contrato.tp_contrato;
            if (contrato.contratos_id_pai > 0) {
                tp_contrato = contrato.tp_contrato + " de acordo";
            }

            $('#md_edit_parcelas_bd_head').html(contrato.descricao + ', Contrato ID: ' + contrato_id + " de " +
                ConverteData(contrato.dt_contrato) + ' (' + tp_contrato + ')');

            info = '<div class="row mg-bt-20">';

            info += '<div class="col-xs-12 col-md-4">';
            info += "<strong>Contrato:</strong> <br>";
            info += "Valor Total do contrato: R$ " + number_format(contrato.vl_contrato, 2) + "<br>";
            info += "Evento: " + contrato.evento_nome + " <br>";
            info += "Status: " + contrato.status + " ";
            if (contrato.motivo_zerado != null) {
                info += "<br> Motivo parcelas zeradas: " + contrato.motivo_zerado;
            }
            if (contrato.observacao_zerado != null) {
                info += "<br> Obs.: " + contrato.observacao_zerado;
            }

            info += '</div>';

            info += '<div class="col-xs-12 col-md-4">';
            info += "<strong>Vendedor:</strong> " + contrato.vendedor_nome + " (" + contrato.vendedor_cpf_cnpj +
                ")<br>";
            if (contrato.vendedor_email != null) info += contrato.vendedor_email + "<br>";
            if (contrato.vendedor_telefone != null) info += contrato.vendedor_telefone + "<br>";
            if (contrato.vendedor_celular != null) info += contrato.vendedor_celular + "<br>";
            if (contrato.vendedor_rua != null) info += contrato.vendedor_rua + " ";
            if (contrato.vendedor_numero != null) info += contrato.vendedor_numero + " ";
            if (contrato.vendedor_bairro != null) info += contrato.vendedor_bairro + " ";
            if (contrato.vendedor_cidade != null) info += contrato.vendedor_cidade + " ";
            if (contrato.vendedor_estado != null) info += contrato.vendedor_estado + " ";
            if (contrato.vendedor_cep != null) info += contrato.vendedor_cep + " ";
            info += '</div>';

            info += '<div class="col-xs-12 col-md-4">';
            info += "<strong>Comprador:</strong> " + contrato.comprador_nome + " (" + contrato
                .comprador_cpf_cnpj + ")<br>";

            if (contrato.comprador_email != null) info += contrato.comprador_email + "<br>";
            if (contrato.comprador_telefone != null) info += contrato.comprador_telefone + "<br>";
            if (contrato.comprador_celular != null) info += contrato.comprador_celular + "<br>";
            if (contrato.comprador_rua != null) info += contrato.comprador_rua + " ";
            if (contrato.comprador_numero != null) info += contrato.comprador_numero + " ";
            if (contrato.comprador_bairro != null) info += contrato.comprador_bairro + " ";
            if (contrato.comprador_cidade != null) info += contrato.comprador_cidade + " ";
            if (contrato.comprador_estado != null) info += contrato.comprador_estado + " ";
            if (contrato.comprador_cep != null) info += contrato.comprador_cep + " ";
            info += '</div>';

            info += '</div>';

            parcelas_pagas = parseInt(contrato.pc_liqd);
            parcelas_atrasadas = parseInt(contrato.pc_atrasada);
            parcelas_a_vencer = parseInt(contrato.pc_total) - (parcelas_pagas + parcelas_atrasadas);

            info += `
                <div class="row">
                    <div class="col-md-4">
                        <h4><span style="color:navy">Pagas: ${parcelas_pagas}</span></h4>
                    </div>
                    <div class="col-md-4">
                        <h4><span style="color:red">Atrasadas: ${parcelas_atrasadas}</span></h4>                        
                    </div>
                    <div class="col-md-4">
                        <h4><span style="color:green">A vencer: ${parcelas_a_vencer}</span></h4>                        
                    </div>
                </div>
            `;

            $('#md_edit_parcelas_bd_body').html(info);

            $('#md_edit_parcelas_bd_body_form').html(result);


            $('.vl_parc_contrato').maskMoney();
            $('.vl_mask').maskMoney();

            $('#inputJurosSimulacao').maskMoney();
            $('#inputHonorInadimpSimulacao').maskMoney();

            $('.dt_parc_contrato').mask("99/99/9999");
            $('.dt_parc_contrato').datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('#inputDtPArcAcordo').mask("99/99/9999");
            $('#inputDtPArcAcordo').datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('#md_edit_parcelas').modal('show');
        }
    });

}

function editar_parcelas() {
    $('button').attr('disabled','disabled');

    if (ajusta_valor() == true) {

        parcelas = $('#form_edit_parcela').serializeArray();
        id_contrato = $("input#inputIdSimulacao").val();
        status_contrato = $("input#inputStatusContrato").val();

        //alert(JSON.stringify(pessoa));
        $.post("<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=edit_parcelas"; ?>", {
            parcelas: parcelas,
            id_contrato: id_contrato,
            status_contrato: status_contrato
        }, function(result) {
            if (result == 1) {
                //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")

                document.getElementById("cont_exibidos").value = 0;
                carregar_resultados();
                $('#md_edit_parcelas').modal('hide');
                jAlert('Parcelas Atualizadas com sucesso', 'Bom trabalho!', 'ok');

            } else {
                if (result == '9') {
                    jAlert('Contrato atualizado, favor recarregar as informações!',
                        'Não foi possível salvar as alterações!', 'alert');
                } else {
                    jAlert('Não foi possível salvar as alterações!', 'Oops', 'alert');
                }
            }
            $('button').removeAttr('disabled');
        });
    }

}
<!-- FIM PARCELAS -->

<!-- INICIO  BOLETOS  -->

function customizar_instrucoes() {
    instrucao_atual = $('#recoverInstrucao').val();
    if (instrucao_atual.length < 2) {
        $('#inputInstrucao1').val('');
        $('#inputInstrucao2').val('');
        $('#inputInstrucao3').val('');
    } else {
        arr_instrucao = instrucao_atual.split("<br>");
        for (i = 0; i < arr_instrucao.length; i++) {
            inst_part = arr_instrucao[i];
            if (inst_part && inst_part.length > 3) {
                j = i + 1;
                $('#inputInstrucao' + j).val(inst_part);
            }
        }
    }
    $('#md_edit_instrucoes').removeClass('hidden');
}

function save_customizar_instrucoes(opcoes) {
    ctId = $('#inputIdAcordo').val();
    inst1 = $('#inputInstrucao1').val();
    inst2 = $('#inputInstrucao2').val();
    inst3 = $('#inputInstrucao3').val();

    if (opcoes == 1 || opcoes == 2) {
        $('#recoverInstrucao').val('');
    }

    $.post("<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=save_instrucao"; ?>", {
        ctId: ctId,
        inst1: inst1,
        inst2: inst2,
        inst3: inst3,
        opcoes: opcoes,
    }, function(result) {
        if (result == 1) {
            cancel_customizar_instrucoes();
            jAlert('Instrução salva com sucesso', 'Bom trabalho!', 'ok');
        } else {
            jAlert('Não foi possível salvar as alterações!', 'Oops', 'alert');
        }
    });

}

function cancel_customizar_instrucoes() {
    $('#md_edit_instrucoes').addClass('hidden');
    $('#inputInstrucao1').val('');
    $('#inputInstrucao2').val('');
    $('#inputInstrucao3').val('');

}


function gerar_boletos(id_contrato) {

    $('#mdGerandoBoletos').modal('show');
    $.get("<?php echo $link . "/inc/boleto/gerar_boletos.php?id="; ?>" + id_contrato, {}, function(result) {
        //alert(result);
        $('#mdGerandoBoletos').modal('hide');
        if (result == 0) {
            //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")						
            jAlert('Não foi possível gerar os boletos', 'Oops', 'alert');

        } else {
            jAlert('Boletos Gerados com sucesso', 'Bom trabalho!', 'ok');

            botao_novo = '<a href="' + result +
                '" target="_blank"  onClick="reset_btn();"  ><input type="button" class="btn mg-tp-15  btn-success" value="Baixar Boletos" id="popup_downzipbol"></a>';

            $('#popup_ok').addClass('hidden');
            $('#popup_panel').append(botao_novo);
        }
    });

}

function reset_btn() {
    $('#popup_downzipbol').addClass('hidden');
    setTimeout(function() {
        $('#popup_downzipbol').remove();
        $('#popup_ok').removeClass('hidden');
    }, 100);

}

function remove_boletos() {
    numero_contrato = $('#input_remove_boletos').val();
    if ($.isNumeric(numero_contrato)) {

        jConfirm('Remover boletos e arquivo remessa do contrato ' + numero_contrato, 'Remover boletos?', function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=remove_boletos"; ?>", {
                        contrato_id: numero_contrato,
                        u: <?php echo $_SESSION['id']; ?>
                    },
                    function(result) {
                        if (result == 1) {
                            $('#remove_boletos').addClass('hidden');
                            $('#input_remove_boletos').val('');
                            $('#md_cadastro_contratos').modal('hide');
                            $('#cont_exibidos').val('0');
                            $('#permite_carregar').val('1');
                            filtrar = 1;
                            carregar_totais();
                            carregar_resultados();
                            jAlert(result.msg, 'Bom trabalho!', 'ok');

                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                        }
                    });
            } else {
                jAlert('As informações estão salvas. ', 'Ação cancelada', 'ok');
            }
        });

    } else {
        jAlert('Não foi possível remover - Ct not numeric: ' + numero_contrato, 'Oops');
    }
}

<!-- FIM  GERAR BOLETOS  -->

function gera_segunda_via_parcela(id_parcela, contrato_id) {
    jAlert('Segunda via apenas pelo ambiente do banco!', 'Oops');
    return 0;
    <?php
                            /*
							$dt_venc = new DateTime(date('Y-m-d'));
							$dt_venc->add(new DateInterval("P2D"));
							?>

    jPrompt('Informe a nova data de vencimento', '<?php //echo $dt_venc->format("d/m/Y");?>', '2° via de boleto',
        function(text) {

            if (text == null) {
                // cancelou ação
            } else if (text.length < 1) {
                jAlert('Preencha a data de vencimento', 'Oops');
            } else {
                //alert('Chama ajax - Id parcela: '+id_parcela+' - Nova data de vencimento: '+text);
                $.post("<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=atualiza_parcelas_2_via";?>", {
                    parcela_id: id_parcela,
                    dt_atualizacao: ConverteData(text),
                }, function(result) {
                    if (result == 1) {
                        get_parcelas_pos_simulacao(contrato_id)
                        jAlert('Valores atualizados', 'Bom trabalho!', 'ok');
                    } else {
                        jAlert('Não foi possível salvar as alterações!', 'Oops', 'alert');
                    }
                });

            }

        });
    <?php
							*/
        ?>
}


function desfazer_liquid_parc(id_parcela) {
    if ($.isNumeric(id_parcela)) {

        jConfirm('Você perderá a rastreabilidade do pagamento atual desta parcela.',
            'Desfazer pagamento desta parcela?',
            function(r) {
                if (r) {
                    $.getJSON(
                        "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=desfazer_pg_parcela"; ?>", {
                            id_parcela: id_parcela,
                            u: <?php echo $_SESSION['id']; ?>
                        },
                        function(result) {
                            if (result.status == 1) {

                                $('#desliq_parc_' + id_parcela).remove();
                                $('#st_parc_' + id_parcela).html(
                                    '<span class="fs-13 blue_light">O pagamento desta parcela foi removido. Atualize para recarregar.</span>'
                                );
                                jAlert(result.msg, 'Bom trabalho!', 'ok');
                                filtrar_fields();

                            } else {
                                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                            }
                        });
                } else {
                    jAlert('As informações estão salvas. ', 'Ação cancelada', 'ok');
                }
            });

    } else {
        jAlert('Não foi possível desfazer o pagamento desta parcela.', 'Oops');
    }

}
$('#OcorStatus').change(function(){
    if ($(this).val() == 'Promessa de pagamento'){
        $('#promessaPagamentoDiv').removeClass('hidden');
    } else {
        $('#promessaPagamentoDiv').addClass('hidden');
    }
});
<?php
if (isset($_REQUEST['solicita_suspensao']) && $_REQUEST['solicita_suspensao']){
    $contrato = $_REQUEST['id'];
    $motivo = $_REQUEST['motivo'];
    $supervisor = $_REQUEST['pessoas_id'];
    ?>
    $(document).ready(function(){
        $('#md_confirma_suspensao').modal('show');
    });
    function respostaSuspensaoContrato(resposta){
        event.preventDefault();
        if (resposta == 'N') {
            $.getJSON(
                "<?php echo $link . "/repositories/alertas/alertas.ctrl.php?acao=inserir_alerta"; ?>", {
                    contrato: <?php echo $contrato; ?>,
                    descricao: `A suspensão do contrato <?php echo $contrato;?> foi negada:\n
                    Motivo:\n
                    ${$('#respostaSuspensao').val()}`,
                    pessoas_id: <?php echo $supervisor;?>,
                    link:'<?php echo "$link/contratos/$contrato"?>'
                },
                function(result) {
                    if (result.status > 0) {
                        jAlert(result.msg, 'A sua resposta foi encaminhada.', 'ok');
                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                    }
                    window.location.href = '<?php echo $link;?>/contratos/<?php echo $contrato;?>';
            });            
        } else if(resposta == 'S') {
            $.getJSON(
                "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=suspenderContrato"; ?>", {
                    contrato: <?php echo $contrato; ?>,
                    pessoas_id:<?php echo $supervisor; ?>
                },
                function(result) {
                    $.getJSON(
                        "<?php echo $link . "/repositories/alertas/alertas.ctrl.php?acao=inserir_alerta"; ?>", {
                            contrato: <?php echo $contrato; ?>,
                            descricao: `A suspensão do contrato <?php echo $contrato;?> foi aceita:\n
                            Motivo:\n
                            ${$('#respostaSuspensao').val()}`,
                            pessoas_id: <?php echo $supervisor;?>,
                            link:'<?php echo "$link/contratos/$contrato"?>'
                        },
                        function(result) {
                            if (result.status > 0) {
                                jAlert(result.msg, 'A sua resposta foi encaminhada.', 'ok');
                            } else {
                                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                            }
                            window.location.href = '<?php echo $link;?>/contratos/<?php echo $contrato;?>';
                    });
            });
        }
    }
    <?php
}
?>
</script>

</body>

</html>
