<?php 
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");
if ($_SESSION['operador']== 'N') {
    header('Location:/dashboard');
}


$menu_active = "carteiras";
$layout_title = "MECOB - Lista de Carteiras";
$sub_menu_active = "operadores";
$tit_pagina = "Lista de Carteiras";
$tit_lista = " Lista de Carteiras";

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
                    <li class="hidden"><a href="#">Rodízios</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;
                    </li>
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
                                        <table id="listagem_rodizios" class="table table-hover table-bordered">
                                            <thead id="thead_rodizios">
                                                <tr>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_rodizios">
                                                <tr>
                                                    <td id="td_carregando" colspan="10">Carregando rodizios</td>
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

<!-- fim cadastro de pessoas-->
<?php include $raiz . "/js/corejs.php"; ?>
<script src="<?php echo $link; ?>/js/jquery.form.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskedinput-1.1.4.pack.js" /></script>
<script src="<?php echo $link; ?>/js/jquery.validate.js" /></script>
<script src="<?php echo $link; ?>/js/jquery.inputmask.bundle.js"></script>
<script src="<?php echo $link; ?>/js/jquery.maskMoney.js" /></script>
<script>

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

    $('#tbody_rodizios').html('<tr><td colspan="10">Carregando rodizios</td></tr>');

    $('#cont_exibidos').val('0');
    $('#permite_carregar').val('1');
    filtrar = 1;

}

function carregar_resultados(open_parcelas) {
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
        data: {
            acao: "carregarListaOperador",
            ultimosFeitos: 'N'
        }
    }).done(function(data) {
        html = `
            <tr>
                <th>Contrato</th>
                <th>Vendedor</th>
                <th>Comprador</th>
                <th>Vencimento</th>
                <th>Parcela</th>
                <th>Valor</th>
                <th>Telefone</th>
                <th>Celular</th>
                <th></th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        data.forEach(element => {
            var celular = (element.celular != null) ? element.celular : '';
            var telefone = (element.telefone != null) ? element.telefone : '';
            html += `
                <tr>
                    <td><a href="<?php echo $link ?>/contratos/${element.contratos_id}" target="_blank">${element.contratos_id}</a></td>
                    <td><a href="<?php echo $link ?>/pessoa/${element.id_vendedor}" target="_blank">${element.nome_vendedor}</a></td>
                    <td><a href="<?php echo $link ?>/pessoa/${element.id_comprador}" target="_blank">${element.nome_comprador}</a></td>
                    <td>${element.dt_vencimento}</td>
                    <td>${element.nu_parcela}</td>
                    <td>${parseFloat(element.vl_corrigido).toLocaleString('pt-BR',{ style: 'currency', currency: 'BRL' })}</td>
                    <td>${telefone}</td>
                    <td>${celular}</td>
                    <td><button class='btn-link informarLigacao' data-contrato="${element.contratos_id}" title='Informar ligação do contrato ${element.contratos_id}'><i class='fa fa-phone' /></button></td>
                </tr>
            `;    
        });
        
        $('#tbody_rodizios').html(html);
    });
}

$(document).on('click', '.gerarRodizio', function() {
    event.preventDefault();
    jConfirm(`Deseja gerar um novo rodízio?`, `Gerar rodízio?`, function(r) {
        $.ajax({
            method: "POST",
            url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
            data: {
                acao: "gerarRodizio"
            }
        }).done(function(data) {
            carregar_resultados();
        });
    });
});
$(document).on('click', '.abre_rodizio', function() {
    event.preventDefault();
    id = $(this).data('id');
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
        data: {
            acao: "carregarRodizioId",
            id:id
        }
    }).done(function(data) {
        html = `
            <tr>
                <th>Operador</th>
                <th>Quantidade de clientes</th>
                <th>Id do rodízio</th>
                <th></th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        data.forEach(element => {
            
            html += `
                <tr>
                    <td><button class="btn-link abre_pessoa" data-id="${element.id_rodizio}" data-pessoa="${element.id}">${element.nome}</button></td>
                    <td>${element.qtd}</td>
                    <td>${element.id_rodizio}</td>
                    <td></td>
                </tr>
            `;    
        });
        
        $('#tbody_rodizios').html(html);
    });
});

$(document).on('click', '.abre_pessoa', function() {
    event.preventDefault();
    id = $(this).data('id');
    pessoa = $(this).data('pessoa');

    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
        data: {
            acao: "carregarRodizioPessoa",
            id:id,
            pessoa:pessoa
        }
    }).done(function(data) {
        html = `
            <tr>
                <th>Vendedor</th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        data.forEach(element => {
            
            html += `
                <tr>
                    <td>${element.nome}</td>
                </tr>
            `;    
        });
        
        $('#tbody_rodizios').html(html);
    });
});

$(document).on('click', '.ativar_rodizio', function() {
    event.preventDefault();
    id = $(this).data('id');

    jConfirm(`Deseja ativar este rodízio e utilizá-lo como rodízio atual?`, `Ativar rodízio ${id}?`, function(r) {
        $.ajax({
            method: "POST",
            url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
            data: {
                acao: "ativarRodizio",
                id:id
            }
        }).done(function(data) {
            carregar_resultados();
        });
    });
});

$(document).on('click','.informarLigacao',function(){
    event.preventDefault();
    $('#md_ocorrencia').modal('show');
    $('#OcorContrato').val($(this).data('contrato'));
});

$(document).on('change','#ocorStatus',function(){
    if ($(this).val() == 'Promessa de pagamento') {
        $('#promessaPagamentoDiv').removeClass('hidden');
    } else {
        $('#promessaPagamentoDiv').addClass('hidden');        
    }
});

$(document).on('click','#btn-save-ligacao',function(){
    event.preventDefault();    
    msg = document.getElementById('OcorMensagem').value.replace(/(^\s+|\s+$)/g, ' ');
    if (msg.length == 0) {
        jAlert("Preencha a mensagem/observação.");
    } else {
        $('#btn-save-ocor').addClass('hidden');
        ocorrencia = $('#form_ocorrencia').serializeArray();
        $.getJSON("<?php echo $link . "/repositories/ocorrencias/ocorrencias.ctrl.php?acao=inserir"; ?>", {
            ocorrencias: ocorrencia
        }, function(result) {
            $('#md_ocorrencia').modal('hide');
            carregar_resultados();
            if (result.status == 1) {
                jAlert(result.msg, 'Bom trabalho!', 'ok');
            } else {
                jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
            }
        });
    }
});

carregar_resultados();
</script>


<div class="modal fade" id="md_ocorrencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informar ligação</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-bordo">
                    <div class="panel-heading">
                        Informar de Ligação</div>
                    <div class="panel-body pan">
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
                                    <textarea id="OcorMensagem" rows="5" style="width:100%;" name="mensagem"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button id="btn-save-ligacao" type="button" class="btn btn-brown">Salvar</button>
            </div>
        </div>
    </div>
</div>
</body>

</html>