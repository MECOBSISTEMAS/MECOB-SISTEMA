					<?php
						include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");						
					?>
					<form id="form_contratos" action="javascript:salvarFormulario()">
					    <input id="inputId" type="hidden" name="id" placeholder="Id" class="form-control" />
					    <input id="inputStatus" type="hidden" name="status" placeholder="Status" class="form-control" />
					    <div class="form-body pal pd-tp-0">
					        <h3 class="mg-tp-0">Informações do Contrato</h3>
					        <div class="row">
					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Tipo de Contrato:</div>
					                    <select id="TpContrato" name="tp_contrato" type="text" placeholder="Tipo de Contrato"
					                        class="form-control  with-placeholder control_edit_contrato_input">
					                        <option value="adimplencia">Adimplência</option>
					                        <option value="inadimplencia">Inadimplência</option>
					                    </select>

					                </div>
					            </div>
					            <div class="col-md-9">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Descrição do contrato:</div>
					                    <input id="inputDescricao" name="descricao" type="text" placeholder="Nome"
					                        class="form-control  with-placeholder control_edit_contrato_input" required="required" />
					                </div>
					            </div>

					        </div>
					        <div class="row">
					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Data do Contrato:</div>
					                    <input id="inputDtContrato" name="dt_contrato" type="text" placeholder="Telefone"
					                        class="form-control  with-placeholder control_edit_contrato_input" />
					                </div>
					            </div>

					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Número de Parcelas:</div>
					                    <select id="selectParcelas" name="nu_parcelas"
					                        class=" form-control  with-placeholder control_edit_contrato_input">
					                        <?php for($i=1;$i<=100;$i++){ ?>
					                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
					                        <?php } ?>
					                    </select>
					                </div>
					            </div>

					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Valor do Contrato:</div>
					                    <input id="inputVlContrato" name="vl_contrato" type="text"
					                        class="form-control  with-placeholder control_edit_contrato_input" />
					                </div>
					            </div>
					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Valor da parcela:</div>
					                    <input id="inputVlEntrada" name="vl_entrada" type="text"
					                        class="form-control  with-placeholder control_edit_contrato_input" />
					                </div>
					            </div>

					        </div>

					        <div class="row">
					            <div class="col-md-3">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Número da 1ª parcela a ser recebida:</div>
					                    <input id="inputNuParcPagto" name="parcela_primeiro_pagto" type="text" placeholder="Nº parcela"
					                        class="form-control  with-placeholder control_edit_contrato_input" />
					                </div>
					            </div>


					            <div class="col-md-4">
					                <div class="form-group">
					                    <input id="inputGerarBoleto" name="gerar_boleto" type="checkbox" value="S" checked="checked"
					                        onclick="botao_gerar_boletos()" /> Gerar Boletos e arquivos de remessa para este contrato?

					                    <?php if(consultaPermissao($ck_mksist_permissao,"cad_contratos","editar")){  ?>
					                    <div id="remove_boletos" class="hidden red_light pointer" onclick="remove_boletos();">
					                        <input type="hidden" id="input_remove_boletos" />
					                        Remover boletos + arquivo remessa
					                    </div>
					                    <div class="hidden red_light pointer" id="bt_gerar_boletos" onclick="gerar_arquivo_remessa()"
					                        value="">
					                        Gerar arquivos e boletos
					                    </div>
					                    <?php } ?>

					                </div>
					            </div>

					        </div>

					        <div class="row">
					            <div class="col-md-4">
					                <h3>Leilão
					                    <a href="<?php echo $link."/eventos";?>" target="_blank"
					                        class="pull-right fs-18 green_light control_edit_contrato_div"><i
					                            class="fa fa-plus-square fs-18" aria-hidden="true"></i> Cadastrar Evento</a>

					                    <div class="pull-right fs-20 blue_light control_edit_contrato_div mg-rg-20 pointer"
					                        onclick="venda_direta();">
					                        <i class="fa fa-suitcase fs-18" aria-hidden="true"></i>
					                    </div>

					                </h3>

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
					                            <input id="inputEvento" name="findevento" type="text" placeholder="Evento"
					                                class="form-control  with-placeholder" autocomplete="off"
					                                onkeyup="busca_evento();" />
					                            <input id="inputEventoId" name="eventos_id" type="hidden" />
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
					                <h3>Vendedor <a href="<?php echo $link."/vendedores";?>" target="_blank"
					                        class="pull-right fs-18 green_light control_edit_contrato_div"><i
					                            class="fa fa-plus-square fs-18" aria-hidden="true"></i> Cadastrar Vendedor</a> </h3>

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
					                            <input id="inputVendedor" name="findvendedor" type="text" placeholder="Vendedor"
					                                class="form-control  with-placeholder" autocomplete="off"
					                                onkeyup="busca_pessoa('vendedores');" />
					                            <input id="inputVendedorId" name="vendedor_id" type="hidden" />
					                            <input id="inputVendedorHonorAdimp" name="honor_adimp" type="hidden" />
					                            <input id="inputVendedorHonorInadimp" name="honor_inadimp" type="hidden" />
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
					                <h3>Comprador <a href="<?php echo $link."/compradores";?>" target="_blank"
					                        class="pull-right fs-18 green_light control_edit_contrato_div"><i
					                            class="fa fa-plus-square fs-18" aria-hidden="true"></i> Cadastrar Comprador</a> </h3>

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
					                            <input id="inputComprador" name="findcomprador" type="text" placeholder="Comprador"
					                                class="form-control  with-placeholder" autocomplete="off"
					                                onkeyup="busca_pessoa('compradores');" />
					                            <input id="inputCompradorId" name="comprador_id" type="hidden" />
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

					    </div>
					    <button type="submit" class="hidden"></button>
					</form>

					<script>
/*  AJAX EVENTO */

function busca_evento() {
    palavra = $('#inputEvento').val();
    tam_palavra = palavra.length;
    if (tam_palavra >= 3) {
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

/*  AJAX PESSOAS = COMPRADOR E VENDEDOR  */

function busca_pessoa(tipo_pessoa) {

    if (tipo_pessoa == 'vendedores') {
        palavra = $('#inputVendedor').val();
    } else {
        palavra = $('#inputComprador').val();
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
    if (tipo_pessoa == 'vendedores') {
        $('#inputVendedorId').val(pessoa.id);
        $('#inputVendedorHonorAdimp').val(pessoa.honor_adimp);
        $('#inputVendedorHonorInadimp').val(pessoa.honor_inadimp);
    } else {
        $('#inputCompradorId').val(pessoa.id);
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
        $('#inputVendedorHonorAdimp').val('');
        $('#inputVendedorHonorInadimp').val('');
        $('#quadro_vendedores').addClass('hidden');
        $('#select_vendedores').removeClass('hidden');
    } else {
        $('#inputCompradorId').val('');
        $('#inputVendedorId').val('');
        $('#quadro_compradores').addClass('hidden');
        $('#select_compradores').removeClass('hidden');
        $('#quadro_vendedores').addClass('hidden');
        $('#select_vendedores').removeClass('hidden');
    }

}

function botao_gerar_boletos() {
    id_contrato = $("input#inputId").val();
    if (id_contrato != "") {
        $('#bt_gerar_boletos').removeClass('hidden');
    }
}

function gerar_arquivo_remessa() {
    contrato_id = $('#inputId').val();
    if ($.isNumeric(contrato_id)) {

        jConfirm('Gerar boletos e arquivo remessa do contrato ' + contrato_id, 'Gerar boletos?', function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link."/inc/boleto/processadores/GARB/gerar_arquivo.php?acao=Z2VyYXJfYXJxdWl2bw==";?>", {
                        contrato_id: contrato_id,
                    },
                    function(result) {
                        if (result == 1) {
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
        jAlert('Não foi possível gerar os boletos - Ct não é numérico: ' + contrato_id, 'Oops');
    }
}
					</script>