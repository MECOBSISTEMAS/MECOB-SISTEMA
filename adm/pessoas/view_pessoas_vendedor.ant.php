<?php
//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$contratosDB  = new contratosDB();
// $ct    = new contratos();
// $ct->vendedor_id = $id;

$filtros="";
if($ehCliente){
	//$filtros = array('filtro_ativo'=>1);
}
$filtros = array('filtro_ativo'=>1);
$cfg_filtros = 'vendas';
include("filtros_contrato_ini.php");


// $contratos_vendedor = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros  ,  "c.id desc," ,  0,"N");
//echo '<pre>'; print_r($contratos); echo '</pre>';

?>

<h3 class="mg-tp-0">Contratos de Venda </h3>
<?php 
include("legendas_colors.php");    
?>

<ul class="nav nav-pills nav-justified" style="text-transform:uppercase; font-weight:bold;">
    <li role="presentation" class="bt_gpct bt_gpct_confirmado active pointer" onclick="troca_gpct('confirmado');">
        <a>Confirmados</a></li>
    <li role="presentation" class="bt_gpct bt_gpct_acao_judicial pointer" onclick="troca_gpct('acao_judicial');"><a>Ação
            Judicial</a></li>
    <li role="presentation" class="bt_gpct bt_gpct_virou_inadimplente pointer"
        onclick="troca_gpct('virou_inadimplente');"><a>Virou Inadimplente</a></li>
    <li role="presentation" class="bt_gpct bt_gpct_pendente pointer" onclick="troca_gpct('pendente');">
        <a>Inadimplência</a></li>
</ul>
<br />
<script>
function troca_gpct(group) {
    $('.bt_gpct').removeClass('active');
    $('.bt_gpct_' + group).addClass('active');

    $('.ctgp').addClass('hidden');
    $('.ctgp_' + group).removeClass('hidden');
}
</script>

<?php  
include("filtros_contrato.php");   
?>
<div class="row">
    <div class="col-sm-4">
        <label for='ordem_vendedor'>Ordenar</label>
        <select id="ordem_vendedor" class="form-control">
            <option value="codigo">Código do contrato</option>
            <option value="descricao">Descrição do contrato</option>
            <option value="nome">Nome do Comprador</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">&nbsp;</div>
</div>

<div id="totalContratosVendedor"></div>
<div id="totais_contratos" class="fs-20" style="text-transform:uppercase">
    <div id="tt_contratos_confirmados" class="ctgp ctgp_confirmado">
    </div>
    <div id="tt_contratos_acao" class="ctgp ctgp_acao_judicial hidden">
    </div>
    <div id="tt_contratos_virou" class="ctgp ctgp_virou_inadimplente hidden">
    </div>
    <div id="tt_contratos_inadp" class="ctgp ctgp_pendente hidden">
    </div>
    <br />
</div>
<div class="panel-group" id="accordionVend" role="tablist" aria-multiselectable="true">
    <!-- 
    ////////////////////////
    CARREGANDO CONTRATOS
    ////////////////////////
    -->
</div>
<script>
var array_parcelas_carregadas = new Array();

function carrega_parcelas_vendedor(id,status) {
    verifica_carregou = array_parcelas_carregadas.indexOf(id);
    //alert(verifica_carregou);
    if (verifica_carregou >= 0) {
        //JÁ CARREGOU PARCELAS 
    } else {
        array_parcelas_carregadas.push(id);
        $.getJSON("<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=lista_parcelas";?>", {
            contrato_id: id
        }, function(result) {
            html_parcelas = '<table class="table table-striped"><thead><tr>';
            html_parcelas += '<th>Parcela</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Valor</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Vencimento</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Pagamento</th>';

            html_parcelas += '</tr> </thead> <tbody>';

            for (i = 0; i < result.length; i++) {
                j = i + 1;

                if (status == 'virou_inadimplente'){
                    stt_parcela = 'Virou Inadimplente';                    
                } else {
                    stt_parcela = "A vencer";
                    if (result[i].dt_pagto != null && result[i].dt_pagto != '0000-00-00') {
                        stt_parcela = "Liquidada em " + ConverteData(result[i].dt_pagto);

                        if (result[i].teds_id) {
                            stt_parcela += " - Pagto na TED id: " + result[i].teds_id;
                        }
                    } else if (maior_data(result[i].dt_vencimento, '<?php echo date('Y-m-d');?>') == 2) {
                        stt_parcela = "Atrasada";
                    }

                    if (result[i].fl_negativada == "S" && (result[i].dt_pagto == null || result[i].dt_pagto == '0000-00-00')) {
                        stt_parcela += " (Negativada) ";
                    }
                }

                //alert('pc:'+result[0].nu_parcela);
                html_parcelas += '<tr>';
                html_parcelas += '<td>' + result[i].nu_parcela;
                html_parcelas += '<div class="visible-xs visible-sm">';
                vl_parcela_exibir = result[i].vl_corrigido;
                if (result[i].dt_pagto != null && result[i].dt_pagto != '0000-00-00') {
                    vl_parcela_exibir = result[i].vl_pagto;
                }
                html_parcelas += 'Valor <br>R$ ' + number_format(vl_parcela_exibir, 2);
                html_parcelas += '<br>Vencimento <br> ' + ConverteData(result[i].dt_vencimento) + "<br>";
                html_parcelas += stt_parcela;
                html_parcelas += '</div>';
                html_parcelas += '</td>';


                html_parcelas += '<td class="hidden-xs hidden-sm">R$ ' + number_format(vl_parcela_exibir, 2) +
                    '</td>';
                html_parcelas += '<td class="hidden-xs hidden-sm">' + ConverteData(result[i].dt_vencimento) +
                    '</td>';
                html_parcelas += '<td class="hidden-xs hidden-sm">' + stt_parcela + '</td>';


                html_parcelas += '</tr>';

            }

            html_parcelas += '</tbody> </table>';
            $('#parcelas_contrato_' + id).html(html_parcelas);


        });



        $.getJSON('<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=lista_documentos";?>', {
            contrato_id: id,
            ajax: 'true'
        }, function(j) {
            cont_novos = 0;
            novos = "";
            //alert(JSON.stringify(j));


            novos = '<table id="list_docs" class="table table table-hover table-bordered">';
            novos += '<thead>';
            novos += '<tr>';
            novos += '<th>Documento</th>';
            novos += '<th>Ação</th>';
            novos += '</tr>';
            novos += '</thead>';
            novos += '<tbody id="tbody_docs">';



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

                novos += "<a href='<?php echo $link."/documentos/";?>" + j[i].file +
                    "' target='_blank' ><span class='pointer' data-toggle='tooltip' data-placement='left' title='Ver documento' data-original-title='Ver documento'  > <i class='fa fa-eye fs-19' > </i></span> </a>";
                novos += "</td>";

                novos += '</tr>';
            }

            if (cont_novos == 0) {
                novos += "<tr><td colspan='10'>Nenhum documento a recuperar</td></tr>";
            }
            novos += '</tbody>';
            novos += '</table>';

            $('#documentos_contrato_' + id).html(novos);

        });
        load_ocorrencia(id);
    }
}

function load_ocorrencia(id) {
    $.getJSON(
        '<?php echo $link."/repositories/ocorrencias/ocorrencias.ctrl.php?acao=listar_ultima_ocorrencia_contrato";?>', {
            contrato_id: id,
            ajax: 'true'
        },
        function(j) {
            ocor = "";
            //alert(JSON.stringify(j));

            ocor = "Nenhuma ocorrência para este contrato.";
            for (var i = 0; i < j.length; i++) {
                ocor = "<strong>Última Ocorrência</strong><br>";
                ocor += "<br>" + j[0]['status'] + " - " + ConverteData(j[0]['data_ocorrencia']);
                ocor += "<br>" + j[0]['mensagem'];
            }



            $('#ocorrencia_contrato_' + id).html(ocor);

        });
}

document.ready = function() {
    $(document).on('click', '.enviar_ocorrencia', function() {
        var msg = $(this).parent().parent().children('div:first').children('input')
        var contratos_id = $(this).data('contrato');
        $.ajax({
            method: "POST",
            url: "<?php echo $link ?>/repositories/ocorrencias/ocorrencias.ctrl.php",
            data: {
                acao: "insere_ocorrencia",
                contratos_id: contratos_id,
                msg: msg.val()
            }
        }).done(function(data) {
            data = JSON.parse(data);
            if (data.status == 1) {
								msg.val('');
								load_ocorrencia(contratos_id);
                jAlert(data.msg, 'Bom trabalho!', 'ok');
            } else {
                jAlert(data.msg, 'Não foi possível salvar as alterações!', 'alert');
            }
        });
    });
}

$(document).ready(function(){
    lista_contratos_vendedor();
});

$(document).on('change','#ordem_vendedor',function(){
    event.preventDefault();
    var ordem = $(this).val();

    if (ordem == 'codigo')
        ordem = 'c.id desc,';

    if (ordem == 'descricao')
        ordem = 'c.descricao,';

    if (ordem == 'nome')
        ordem = 'comprador_nome,';

    var filtros = getFiltrosVendedor();    

    lista_contratos_vendedor(ordem,filtros);

});

function lista_contratos_vendedor(ordem = 'c.id desc,',filtros = []){
    // console.log(filtros);
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "listaTotaisContratosVendedor",
            pessoas_id: <?php echo $id?>,
            filtro_contrato: filtros['filtro_contrato'],
            filtro_data: filtros['filtro_data'],
            filtro_evento: filtros['filtro_evento'],
            filtro_comprador: filtros['filtro_comprador'],
            ordem: ordem
        }
    }).done(function(data) {
        data = JSON.parse(data);
        texto = `
            Encontrados ${data.qtd} contratos, totalizando ${parseFloat(data.valor).toLocaleString('pt-BR',{ style: 'currency', currency: 'BRL' })}
        `;
        $('#totalContratosVendedor').html(texto);
    });
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "listaContratosVendedor",
            pessoas_id: <?php echo $id?>,
            filtro_contrato: filtros['filtro_contrato'],
            filtro_data: filtros['filtro_data'],
            filtro_evento: filtros['filtro_evento'],
            filtro_comprador: filtros['filtro_comprador'],
            ordem: ordem
        }
    }).done(function(data) {
        data = JSON.parse(data);
        var html = '';
        data.forEach(d => {
            var cor_stt_contrato = "#5F9EA0";
            if(d.pc_atrasada>0){cor_stt_contrato = "#DE5145";}
            else if(d.pc_total == d.pc_liqd){cor_stt_contrato = "#1BA261";}
            if (d.suspenso == 'S') cor_stt_contrato = '#999';

            if (d.suspenso == 'S'){
                status = '(Status: Suspenso)';
            } else {
                status =  `(Status: ${d.status}`;
            }

            comprador_telefone = (d.comprador_telefone == null) ? '' : d.comprador_telefone;
            comprador_celular = (d.comprador_celular == null) ? '' : d.comprador_celular;
            
            html += `
            <div class="panel panel-default ctgp ctgp_${d.status}">
                <div class="panel-heading pessoa_collapse_title" role="tab" id="heading${d.id}"
                    style=" background-color:${cor_stt_contrato}">
                    <h4 class="panel-title">
                        <a id="openContrato${d.id}" role="button" data-toggle="collapse"
                            data-parent="#accordionComp" href="#collapse${d.id}" aria-expanded="true"
                            aria-controls="collapse${d.id}" class="pessoa_collapse_title_a"
                            onClick="carrega_parcelas_vendedor(${d.id} , '${d.status}' );">
                            <i class="fa fa-plus "></i>
                                Evento: ${d.evento_nome}<br>
                            Contrato
                            ${d.id} - ${d.descricao} - ${d.dt_contrato} - Valor: R$ ${d.vl_contrato} ${status} - Pagto: ${d.pc_liqd}/${d.pc_total}
                        </a>
                    </h4>
                </div>
                <div id="collapse${d.id}" class="panel-collapse collapse pessoa_collapse_body"
                    role="tabpanel" aria-labelledby="heading${d.id}">
                    <div class="panel-body">
                        <div class="pessoa_collapse_info">
                            <h4 class="mg-tp-0">Evento</h4>
                            <h5>${d.evento_nome}</h5>
                            <h4 class="mg-tp-0">Comprador</h4>
                            <h5>Nome: ${d.comprador_nome}</h5>
                            <h5>E-mail: ${d.comprador_email}</h5>
                            <h5>Documento: ${d.comprador_cpf_cnpj}</h5>
                            <h5>Telefone:  ${comprador_telefone} / ${comprador_celular} </h5>
                        </div>
                        <div class="col-xs-12 col-md-7 bk-white">
                            <div class="row">
                                <div id="ocorrencia_contrato_${d.id}" class="col-sm-12">
                                    Carregando Ocorrência
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <input type="text" class="form-control ocorrencia_msg"
                                        placeholder="Insira sua mensagem...">
                                </div>
                                <div class="col-sm-2">
                                    <button data-contrato="${d.id}"
                                        class="btn btn-primary enviar_ocorrencia"><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php if($menu_active!='segunda_via'){ ?>
                            <div id="documentos_contrato_${d.id}" class="bk-white">
                                Carregando Documentos...
                            </div>
                        <?php } ?>
                        <div id="parcelas_contrato_${d.id}"
                            class="pessoa_collapse_parcelas bk-whitebk-white">
                            Carregando Parcelas...
                        </div>
                    </div>
                </div>
            </div>
            `;
        });
        $('#accordionVend').html(html);
    });
}
$(document).on('submit','.form_filtros_contratos',function(){
    event.preventDefault();
    
    var filtros = getFiltrosVendedor();
    
    lista_contratos_vendedor('c.id desc,',filtros);
});

function getFiltrosVendedor(){
    var filtros = [];
    filtros['filtro_data'] = $($($('.form_filtros_contratos')[1]).find('[name=filtro_data]')).val();
    filtros['filtro_evento'] = $($($('.form_filtros_contratos')[1]).find('[name=filtro_evento]')).val();
    filtros['filtro_contrato'] = $($($('.form_filtros_contratos')[1]).find('[name=filtro_contrato]')).val();
    filtros['filtro_comprador'] = $($($('.form_filtros_contratos')[1]).find('[name=filtro_comprador]')).val();
    return filtros;
}
</script>