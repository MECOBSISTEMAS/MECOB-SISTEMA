<?php 
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");

$menu_active = "carteiras";
$layout_title = "MECOB - Rodízios";
$sub_menu_active = "rodizios";
$tit_pagina = "Rodízios";
$tit_lista = " Lista de rodízios";

$addcss = '<link rel="stylesheet" href="' . $link . '/css/smoothjquery/smoothness-jquery-ui.css">';

include($raiz . "/partial/html_ini.php");

include_once($raiz . "/inc/util.php");

include_once($raiz . "/repositories/rodizios/rodizios.db.php");
$rodizios_DB = new rodiziosDB();


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
                                    <?php 

                                    // if (consultaPermissao($ck_mksist_permissao, "cad_rodizios", "adicionar")) { ?>
                                        <h3><button type="button" class="btn btn-brown gerarRodizio">Gerar novo rodízio</button> <span id="titulo"></span></h3>
                                    <?php 
                                    // }
                                        ?>
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
                                                    <th class="hidden-xs hidden-sm pointer">Id Rodízio</th>
                                                    <th class="hidden-xs hidden-sm pointer">Data de criação</th>
                                                    <th class="hidden-xs hidden-sm pointer">Ativo</th>

                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_rodizios">
                                                <tr>
                                                    <td id="td_carregando" colspan="10">Carregando rodizios</td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <div id="totalContratos"></div>
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
            <form class="hidden" method="post" action="<?php echo $link?>/inc/pdf/gera_pdf_clientes_por_operador.php" id="formPDF">
                <textarea name="contentPDF" id="contentPDF"></textarea>
                <input name="nomeOperador" id="nomeOperador"></textarea>
            </form>
            <!--END CONTENT-->
            <!--BEGIN FOOTER-->
            <?php include($raiz . "/partial/footer.php"); ?>
            <!--END FOOTER-->
        </div>
        <!--END PAGE WRAPPER-->
    </div>
</div>

<div class="modal fade" id="mdAlterarOperador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
data-backdrop="static" data-keyboard="false">
<div class="modal-dialog " role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="md_cadastro_contratos_tt"></h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-bordo">
                <div class="panel-body pan">
                    <div class="pd-lf-15 ac">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group input-icon right">
                                    <strong>Operador:</strong>
                                    <br />
                                    <select name="operador" id="operador" class="form-control">
                                        <?php
                                            $listaOperadores = $rodizios_DB->listarSituacaoAtual($conexao_BD_1);
                                            foreach ($listaOperadores as $key => $value) {
                                                $opId = $value['id'];
                                                $opNome = $value['nome'];
                                                echo "<option value='$opId'>$opNome</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary confirmarAlteracao">Alterar operador</button>
                        <button type="button" class="btn btn-sm btn-danger excluirCarteira">Excluir carteira</button>
                    </div>
                </div>
            </div>
        </div>
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
            acao: "carregarRodizios"
        }
    }).done(function(data) {
        html = `
            <tr>
                <th>Id do Rodízio</th>
                <th>Data de criação</th>
                <th>Ativado</th>
                <th></th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        data.forEach(element => {
            if (element.ativo == 'N'){
                btnAtivar = `<button class="btn-link ativar_rodizio" data-id="${element.id_rodizio}"><i class="fa fa-check"></i></button>`;
            } else {
                btnAtivar = ``;
            }
            html += `
                <tr>
                    <td><button class="btn-link abre_rodizio" data-id="${element.id_rodizio}">${element.id_rodizio}</button></td>
                    <td>${element.data_inicio}</td>
                    <td>${element.ativo}</td>
                    <td>
                        ${btnAtivar}
                    </td>
                </tr>
            `;    
        });
        
        $('#tbody_rodizios').html(html);
        $('#totalContratos').html(``);
    });
}

$(document).on('click', '.gerarRodizio', function() {
    event.preventDefault();
    jConfirm(`Deseja gerar um novo rodízio?`, `Gerar rodízio?`, function(r) {
        if (r){
            $.ajax({
                method: "POST",
                url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
                data: {
                    acao: "gerarRodizio"
                }
            }).done(function(data) {
                carregar_resultados();
            });
        }
    });
});
$(document).on('click', '.voltarRodizios', function() {
    event.preventDefault();
    carregar_resultados();
    $('#titulo').html(`Rodízios`);
});
$(document).on('click', '.abre_rodizio,.voltarOperadores', function() {
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
                    <td><button class="btn-link abre_pessoa" data-qtd_carteiras="${element.qtd}" data-id="${element.id_rodizio}" data-pessoa="${element.id}" data-nome-pessoa="${element.nome}">${element.nome}</button></td>
                    <td>${element.qtd}</td>
                    <td>${element.id_rodizio}</td>
                    <td></td>
                </tr>
            `;    
        });
        
        $('#tbody_rodizios').html(html);
        $('#titulo').html(`Rodízio: ${id}<br/><button style="font-size:9pt;" class="btn-link voltarRodizios">voltar</button>`);
        $('#totalContratos').html(``);
    });
});

$(document).on('click', '.abre_pessoa', function() {
    event.preventDefault();
    id = $(this).data('id');
    pessoa = $(this).data('pessoa');
    pessoa_nome = $(this).data('nome-pessoa');
    qtdCarteiras = $(this).data('qtd_carteiras');

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
                <th>Contratos com parcelas a vencer</th>
                <th></th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        var totalContratos = 0;
        data.forEach(element => {
            
            html += `
                <tr>
                    <td>${element.nome}</td>
                    <td>${element.qtdContratosAVencer}</td>
                    <td><button data-vendedor_id="${element.id}" data-rodizio_id="${element.id_rodizio}" class='btn-link alterarOperador'><i class='fa fa-edit'></i></button></td>
                </tr>
            `;    
            totalContratos += parseInt(element.qtdContratosAVencer);
        });
        
        $('#tbody_rodizios').html(html);
        $('#titulo').html(`
            Operador: ${pessoa_nome} - ${qtdCarteiras} carteiras<br/>
            <button style="" data-id="${id}" class="btn-link"><i class="fa fa-print gerarPDF" title="Gerar PDF"></i></button>
            <button style="font-size:9pt;" data-id="${id}" class="btn-link voltarOperadores">voltar</button>
        `);
        $('#totalContratos').html(`Total de contratos: ${totalContratos}`);
    });
});

$(document).on('click', '.ativar_rodizio', function() {
    event.preventDefault();
    id = $(this).data('id');

    jConfirm(`Deseja ativar este rodízio e utilizá-lo como rodízio atual?`, `Ativar rodízio ${id}?`, function(r) {
        if (r){
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
        }   
    });
});

$(document).on('click', '.alterarOperador', function() {
    event.preventDefault();
    rodizio_alterar = $(this).data('rodizio_id');
    vendedor_alterar = $(this).data('vendedor_id');
    $('.confirmarAlteracao').data('rodizio',rodizio_alterar);
    $('.confirmarAlteracao').data('vendedor',vendedor_alterar);

    $('.excluirCarteira').data('rodizio',rodizio_alterar);
    $('.excluirCarteira').data('vendedor',vendedor_alterar);

    $('#mdAlterarOperador').modal('show');
});

$(document).on('click', '.confirmarAlteracao', function() {
    event.preventDefault();
    rodizio_alterar = $(this).data('rodizio');
    vendedor_alterar = $(this).data('vendedor');
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
        data: {
            acao: "alterarOperador",
            rodizio: rodizio_alterar,
            vendedor: vendedor_alterar,
            operador:$('#operador').val()
        }
    }).done(function(data) {
        // if (data == 1){
            $('#mdAlterarOperador').modal('hide');
            carregar_resultados();
        // }
    });
});

$(document).on('click', '.excluirCarteira', function() {
    event.preventDefault();
    rodizio_alterar = $(this).data('rodizio');
    vendedor_alterar = $(this).data('vendedor');
    jConfirm(`Deseja excluir esta carteira do rodízio?`, `Excluir carteira?`, function(r) {
        if (r){
            $.ajax({
                method: "POST",
                url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
                data: {
                    acao: "excluirCarteira",
                    rodizio: rodizio_alterar,
                    vendedor: vendedor_alterar
                }
            }).done(function(data) {
                // if (data == 1){
                    $('#mdAlterarOperador').modal('hide');
                    carregar_resultados();
                // }
            });
        }
    });
});

$(document).on('click', '.gerarPDF', function() {
    event.preventDefault();
    // console.log($('#listagem').html())
    $('#contentPDF').html($('#listagem').html());
    $('#nomeOperador').val($('#titulo').html());
    $('#formPDF').submit();
});

carregar_resultados();
</script>

</body>

</html>