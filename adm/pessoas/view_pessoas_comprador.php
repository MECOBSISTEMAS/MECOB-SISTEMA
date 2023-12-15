<?php

//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

// $contratosDB  = new contratosDB();
// $ct    = new contratos();
// $ct->comprador_id = $id;

$filtros="";
if($ehCliente){
	//$filtros = array('filtro_ativo'=>1);
}

$filtros = array('filtro_ativo'=>'not_pendente'); 
$cfg_filtros = 'compras';
include("filtros_contrato_ini.php");
// $contratos_comprador = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros ,  "c.id desc," ,  0,"N");
//echo '<pre>'; print_r($contratos_comprador); echo '</pre>';

?>

<h3 class="mg-tp-0">Contratos de Compra </h3>
<div id="legenda_colors" style=" clear:both; float:right; width:200px">
    <div style="height:20px" class="row  ">
        <div class="col-xs-1" style="height:18px; background-color:#5F9EA0">
        </div>
        <div class="col-xs-10">
            A vencer </div>
    </div>
    <div style="height:20px" class="row  ">
        <div class="col-xs-1" style="height:18px; background-color:#1BA261">
        </div>
        <div class="col-xs-10">
            Liquidados </div>
    </div>
    <div style="height:20px" class="row  ">
        <div class="col-xs-1" style="height:18px; background-color:#FF5759">
        </div>
        <div class="col-xs-10">
            Atrasados </div>
		</div>
		<div style="height:20px" class="row  ">
        <div class="col-xs-1" style="height:18px; background-color:#999">
        </div>
        <div class="col-xs-10">
            Suspensos </div>
    </div>
</div>
<div style="clear:both;"><br /></div>
<?php 
include("filtros_contrato.php"); 
?>
<div class="row">
    <div class="col-sm-4">
        <label for='ordem_comprador'>Ordenar</label>
        <select id="ordem_comprador" class="form-control">
            <option value="codigo">Código do contrato</option>
            <option value="descricao">Descrição do contrato</option>
            <option value="nome">Nome do Vendedor</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">&nbsp;</div>
</div>
<div id="totalContratosComprador"></div>
<div class="panel-group" id="accordionComp" role="tablist" aria-multiselectable="true">
    <!-- 
        ////////////////////////
        CARREGANDO CONTRATOS
        ////////////////////////
     -->
</div>

<script>
var array_parcelas_comprador = new Array();

function carrega_parcelas_comprador(id, status) {
    if (array_parcelas_comprador.indexOf(id) >= 0) {
        //JÁ CARREGOU PARCELAS 
    } else {
        array_parcelas_comprador.push(id);
        $.getJSON("<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=lista_parcelas";?>", {
            contrato_id: id
        }, function(result) {
            //alert(JSON.stringify(result));
            possui_boleto = false;
            html_parcelas = '<table class="table table-striped"><thead><tr>';
            html_parcelas += '<th>Parcela</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Valor</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Vencimento</th>';
            html_parcelas += '<th class="hidden-xs hidden-sm">Pagamento</th>';
            html_parcelas += '<th>Boleto</th>';

            html_parcelas += '</tr> </thead> <tbody>';

            gerar_boleto = true;

            for (i = 0; i < result.length; i++) {
                j = i + 1;
                //alert('pc:'+result[0].nu_parcela);


                if (result[i].dt_pagto != null && result[i].dt_pagto != '0000-00-00') {
                    stt_parcela = "Liquidada em " + ConverteData(result[i].dt_pagto);
                    acao_parcela = "";
                } else if (maior_data(result[i].dt_vencimento, '<?php echo date('Y-m-d');?>') == 2 && diffDays(result[i].dt_vencimento) > 59) {
                    stt_parcela = "Atrasada";
                    //acao_parcela = "<span class='pointer'  onClick='gera_segunda_via_parcela("+result[i].id+" , "+id+");'> Gerar 2° via </span>";
                    <?php 
						$auxSegVia = "";
						if($menu_active == 'segunda_via'){
							$auxSegVia = "segvia&";
						}
						?>
                    acao_parcela =
                        '<a href="https://unicred-florianopolis.cobexpress.com.br/default/segunda-via"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> 2° via Boleto </a>';

                } else {
                    stt_parcela = "A vencer";
                    <?php 
						$auxSegVia = "";
						if($menu_active == 'segunda_via'){
							$auxSegVia = "segvia&";
						}
						?>
                    acao_parcela =
                        '<a href="<?php echo $link."/inc/boleto/gerar_boleto.php?".$auxSegVia."id=";?>' + id +
                        '&p=' + result[i].id +
                        '"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> Boleto </a>';
                    possui_boleto = true;
                }

                if (status == 'pendente') {
                    stt_parcela = acao_parcela = "";
                }

                if (result[i].gerar_boleto == 'N') {
                    acao_parcela = "Sem boleto";
                    possui_boleto = false;
                }



                html_parcelas += '<tr>';
                html_parcelas += '<td >';
                html_parcelas += result[i].nu_parcela;
                html_parcelas += '<div class="visible-xs visible-sm">';
                vl_parcela_exibir = result[i].vl_corrigido;
                if (result[i].dt_pagto != null && result[i].dt_pagto != '0000-00-00') {
                    vl_parcela_exibir = result[i].vl_pagto;
                }
                html_parcelas += 'Valor <br>R$ ' + number_format(vl_parcela_exibir, 2);

                html_parcelas += '<br>Vencimento <br> ' + ConverteData(result[i].dt_vencimento) + "<br>";
                html_parcelas += stt_parcela;
                html_parcelas += '</div>';
                html_parcelas += '</td >';



                html_parcelas += '<td class="hidden-xs hidden-sm">R$ ' + number_format(vl_parcela_exibir, 2) +
                    '</td>';
                html_parcelas += '<td class="hidden-xs hidden-sm">' + ConverteData(result[i].dt_vencimento) +
                    '</td>';
                html_parcelas += '<td class="hidden-xs hidden-sm">' + stt_parcela + '</td>';
                html_parcelas += '<td>' + acao_parcela + '</td>';


                html_parcelas += '</tr>';

            }

            html_parcelas += '</tbody> </table>';

            if (possui_boleto) {
                html_parcelas +=
                    '<br><a href="<?php echo $link."/inc/boleto/gerar_boleto.php?".$auxSegVia."id=";?>' + id +
                    '"  target="_blank" class="btn btn-sm btn-info pull-right"> <i class="fa fa-download" aria-hidden="true"></i> Baixar todos Boletos </a>';
            }


            $('#parcelas_contrato_' + id).html(html_parcelas);


        });

        <?php if($menu_active!='segunda_via'){ ?>

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

        <?php } ?>


    }
}



function gera_segunda_via_parcela(id_parcela, contrato_id) {
    jAlert('Segunda via apenas pelo ambiente do banco!', 'Oops');
    return 0;
    <?php
	/*
    $dt_venc = new DateTime(date('Y-m-d'));
	$dt_venc->add(new DateInterval("P2D"));
	?>

    $.post("<?php //echo $link."/repositories/contratos/contratos.ctrl.php?acao=atualiza_parcelas_2_via";?>", {
        parcela_id: id_parcela,
        dt_atualizacao: ConverteData('<?php echo $dt_venc->format("d/m/Y");?>'),
    }, function(result) {
        if (result == 1) {
            jAlert('As informações serão recarregadas!', 'Atualizado!', 'ok');
            $('#popup_ok').on("click", function() {
                <?php //if($menu_active == 'segunda_via'){ ?>
                location.reload();
                <?php //}else{ ?>
                url = '<?php// echo $link."/pessoa_compra/".$_SESSION['id']."/contrato/";?>' +
                    contrato_id;
                window.location = url;
                <?php //}  ?>
            });
        } else {
            jAlert('Não foi possível gerar a segunda via desta parcela - contate a nossa equipe.', 'Oops',
                'alert');
        }
    });
    <?php
    */
    ?>
}
$(document).ready(function(){
    lista_contratos_comprador();
});

$(document).on('change','#ordem_comprador',function(){
    event.preventDefault();
    var ordem = $(this).val();

    if (ordem == 'codigo')
        ordem = 'c.id desc,';

    if (ordem == 'descricao')
        ordem = 'c.descricao,';

    if (ordem == 'nome')
        ordem = 'vendedor_nome,';

    var filtros = getFiltrosComprador();    

    lista_contratos_comprador(ordem,filtros);

});

function lista_contratos_comprador(ordem = 'c.id desc,',filtros=[]){
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "listaTotaisContratosComprador",
            pessoas_id: <?php echo $id?>,
            filtro_contrato: filtros['filtro_contrato'],
            filtro_data: filtros['filtro_data'],
            filtro_evento: filtros['filtro_evento'],
            filtro_comprador: filtros['filtro_comprador'],
            ordem: ordem
        }
    }).done(function(data) {
        data = JSON.parse(data);
        texto = `Encontrados ${data.qtd} contratos`;
        $('#totalContratosComprador').html(texto);
    });
    $.ajax({
        method: "POST",
        url: "<?php echo $link ?>/repositories/contratos/contratos.ctrl.php",
        data: {
            acao: "listaContratosComprador",
            pessoas_id: <?php echo $id?>,
            filtro_contrato: filtros['filtro_contrato'],
            filtro_data: filtros['filtro_data'],
            filtro_evento: filtros['filtro_evento'],
            filtro_vendedor: filtros['filtro_vendedor'],
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

            vendedor_telefone = (d.vendedor_telefone == null) ? '' : d.vendedor_telefone;
            vendedor_celular = (d.vendedor_celular == null) ? '' : d.vendedor_celular;
            
            html += `
            <div class="panel panel-default">
                <div class="panel-heading pessoa_collapse_title" role="tab" id="heading${d.id}"
                    style=" background-color:${cor_stt_contrato}">
                    <h4 class="panel-title">
                        <a id="openContrato${d.id}" role="button" data-toggle="collapse"
                            data-parent="#accordionComp" href="#collapse${d.id}" aria-expanded="true"
                            aria-controls="collapse${d.id}" class="pessoa_collapse_title_a"
                            onClick="carrega_parcelas_comprador(${d.id} , '${d.status}' );">
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
                            <h4 class="mg-tp-0">Vendedor</h4>
                            <h5>Nome: ${d.vendedor_nome}</h5>
                            <h5>E-mail: ${d.vendedor_email}</h5>
                            <h5>Documento: ${d.vendedor_cpf_cnpj}</h5>
                            <h5>Telefone:  ${vendedor_telefone} / ${vendedor_celular} </h5>
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
        $('#accordionComp').html(html);
    });
}
$(document).on('submit','.form_filtros_contratos',function(){
    event.preventDefault();
    
    var filtros = getFiltrosComprador();
    
    lista_contratos_comprador('c.id desc,',filtros);
});

function getFiltrosComprador(){
    var filtros = [];
    filtros['filtro_data'] = $($($('.form_filtros_contratos')[0]).find('[name=filtro_data]')).val();
    filtros['filtro_evento'] = $($($('.form_filtros_contratos')[0]).find('[name=filtro_evento]')).val();
    filtros['filtro_contrato'] = $($($('.form_filtros_contratos')[0]).find('[name=filtro_contrato]')).val();
    filtros['filtro_vendedor'] = $($($('.form_filtros_contratos')[0]).find('[name=filtro_vendedor]')).val();
    return filtros;
}
</script>