<?php 
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');
include_once($raiz . "/inc/combos.php");
include_once($raiz . "/valida_acesso.php");
if ($_SESSION['supervisor'] != 'S' && $_SESSION['perfil_id'] != 1 && $_SESSION['perfil_id'] != 3) {
    header('Location:/dashboard');
}


$menu_active = "carteiras";
$layout_title = "MECOB - Lista de Carteiras";
$sub_menu_active = "gerentes";
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

function carregar_resultados(open_parcelas) {
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
        data: {
            acao: "listarSituacaoAtual"
        }
    }).done(function(data) {
        html = `
            <tr>
                <th>Opereador</th>
            </tr>
        `;
        $('#thead_rodizios').html(html);

        html = '';
        data = (JSON.parse(data));
        data.forEach(element => {
            html += `
                <tr>
                    <td>
                        ${element.nome}
                        <br/>
                        <div style="width:100%;padding-left:20px">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Contrato
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                        <th>
                                            Vencimento
                                        </th>
                                    </tr>
                                </thead> 
                                <tbody id='operador${element.id}'>
                                
                                </tbody>   
                            </table>
                        </div>
                    </td>
                </tr>
            `;
            $.ajax({
                method: "POST",
                url: "<?php echo $link ?>/repositories/rodizios/rodizios.ctrl.php",
                data: {
                    acao: "carregarListaOperador",
                    id:element.id
                }
            }).done(function(data2) {
                data2 = (JSON.parse(data2));
                htmlOperador = '';
                data2.forEach(element2 => {
                    if (element2.feitos == '1')
                        feitos = 'style="background-color:#EEFAE0"';
                    else    
                        feitos = '';
                    htmlOperador += `
                        <tr ${feitos}>
                            <td>
                                ${element2.contratos_id}
                            </td>
                            <td>
                                ${element2.vl_corrigido}
                            </td>
                            <td>
                                ${GetFormattedDate(element2.dt_vencimento)}
                            </td>
                        </tr>   
                    `;  
                });
                console.log(htmlOperador);
                $(`#operador${element.id}`).html(htmlOperador);
            });
        });
        
        $('#tbody_rodizios').html(html);
    });
}
function GetFormattedDate(data) {
    var todayTime = new Date(data);
    var month = todayTime.getMonth() + 1;
    var day = todayTime.getDate();
    var year = todayTime.getFullYear();
    return day + "/" + month + "/" + year;
}
carregar_resultados();

setInterval(() => {
    carregar_resultados();
}, 180000);
</script>

</body>

</html>