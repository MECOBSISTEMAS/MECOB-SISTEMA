					<?php
                    	include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");
					?>
					<form id="form_pessoas" action="javascript:salvarFormulario(0)">
					    <input id="inputId" type="hidden" name="id" placeholder="Id" class="form-control" />
					    <div class="form-body pal">
					        <div id="row_status" class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Status:</div>
					                    <?php combo_status("status_id", "selectStatus", "form-control  with-placeholder"); ?>
					                </div>
					            </div>
					        </div>

					        <div class="row">
					            <div class="col-md-3">
					                <div class="form-group">
					                    <input id="inputEhLeiloeiro" name="eh_leiloeiro" type="checkbox" value="S" /> Leiloeiro
					                </div>
					            </div>

					            <div class="col-md-3">
					                <div class="form-group">
					                    <input id="inputEhComprador" name="eh_comprador" type="checkbox" value="S" /> Comprador
					                </div>
					            </div>

					            <div class="col-md-3">
					                <div class="form-group">
					                    <input id="inputEhVendedor" name="eh_vendedor" type="checkbox" value="S" /> Vendedor
					                </div>
					            </div>

					            <div class="col-md-3">
					                <div class="form-group">
					                    <input id="inputEhUsuario" name="eh_user" type="checkbox" value="S"
					                        onclick="checkboxUserSelectPerfil();" /> Usuário
					                </div>
					            </div>

					        </div>

					        <div id="formSelectPerfil" class="row <?php if(!$lista_usuarios){ echo 'hidden'; }?>">
					            <div class="col-md-8">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Perfil:</div>
					                    <?php combo_perfil("perfil_id", "SelectPerfil", " form-control with-placeholder","","Selecione o Perfil"); ?>
					                </div>
					            </div>
					            <div class="col-md-4">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<input id="inputSupervisor" name="supervisor" type="checkbox" value="S" />
												<label for="inputSupervisor">Supervisor/Gerente</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<input id="inputOperador" name="operador" type="checkbox" value="S" />
												<label for="inputOperador">Operador</label>
											</div>
										</div>
									</div>
					            </div>
					        </div>

					        <div class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Razão Social / Nome:</div>
					                    <input id="inputNome" name="nome" type="text" placeholder="Nome"
					                        class="form-control  with-placeholder" required="required" />
					                </div>
					            </div>

					        </div>
					        <div class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Nome Fantasia / Apelido:</div>
					                    <input id="inputApelido" name="apelido" type="text" placeholder="Apelido / Nome Fantasia"
					                        class="form-control  with-placeholder" />
					                </div>
					            </div>

					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Contato:</div>
					                    <input id="inputContato" name="contato" type="text" placeholder="Contato"
					                        class="form-control  with-placeholder" />
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Data de Nascimento:</div>
					                    <input id="inputDtNasc" name="dt_nascimento" type="text" placeholder="Data Nascimento"
					                        class="form-control  with-placeholder" />
					                </div>
					            </div>

					        </div>
					        <div class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Nacionalidade:</div>
					                    <input id="inputNacionalidade" name="nacionalidade" type="text" placeholder="Nacionalidade"
					                        class="form-control  with-placeholder" value="Brasileiro" />
					                </div>
					            </div>
					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Documento:</div>
					                    <input id="inputCpfCnpj" name="cpf_cnpj" type="number" placeholder="CPF/CNPJ"
					                        class="form-control  with-placeholder color-placeholder" />
					                    <!--onchange="verifica_email_doc_existente('doc');"-->
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">RG / IE:</div>
					                    <input id="inputRg" name="rg" type="text" placeholder="RG / IE"
					                        class="form-control  with-placeholder" />
					                </div>
					            </div>

					        </div>

					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Telefone:</div>
					                    <input id="inputTelefone" name="telefone" type="text" placeholder="(99) 99999-9999"
					                        class="form-control  with-placeholder color-placeholder" />
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Celular:</div>
					                    <input id="inputCelular" name="celular" type="text" placeholder="(99) 99999-9999"
					                        class="form-control  with-placeholder color-placeholder" />
					                </div>
					            </div>
					        </div>

					        <div class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Site:</div>
					                    <input id="inputSite" name="site" type="text" placeholder="Site"
					                        class="form-control  with-placeholder color-placeholder" />
					                </div>
					            </div>
					        </div>

					        <div class="row">
					            <div class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">E-mail:</div>
					                    <input id="inputEmail" name="email" type="text" placeholder="Email"
					                        class="form-control  with-placeholder" />
					                    <!-- onchange="verifica_email_doc_existente('email');"-->
					                </div>
					            </div>

					        </div>

					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Senha:</div>
					                    <input id="inputSenha" name="password" type="password" placeholder="Senha"
					                        class="form-control  with-placeholder" />
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Confirma senha:</div>
					                    <input id="inputSenhaConfirma" name="password_confirma" type="password"
					                        placeholder="Confirma Senha" class="form-control  with-placeholder" />
					                </div>
					            </div>

					        </div>

					        <hr />


					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">CEP:</div>
					                    <input id="inputCep" name="cep" type="text" placeholder="CEP"
					                        class="form-control with-placeholder" onBlur="buscaCep();" /> <span id="btn-cnpj"
					                        class="input-group-btn ">
					                        <a target="_blank" href="http://www.buscacep.correios.com.br/"> ? </a>
					                    </span>
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Rua:</div>
					                    <input id="inputRua" type="text" name="rua" placeholder="Rua"
					                        class="form-control with-placeholder" />
					                </div>
					            </div>

					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Número:</div>
					                    <input id="inputNumero" type="text" name="numero" placeholder="Número"
					                        class="form-control with-placeholder" />
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Bairro:</div>
					                    <input id="inputBairro" type="text" name="bairro" placeholder="Bairro"
					                        class="form-control with-placeholder" />
					                </div>
					            </div>
					        </div>
					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Estado:</div>
					                    <select "form-control" id="inputEstado" name="estado" class="form-control with-placeholder" />
					                    <option value="">Selecione</option>
					                    <option value="AC">Acre</option>
					                    <option value="AL">Alagoas</option>
					                    <option value="AP">Amapá</option>
					                    <option value="AM">Amazonas</option>
					                    <option value="BA">Bahia</option>
					                    <option value="CE">Ceará</option>
					                    <option value="DF">Distrito Federal</option>
					                    <option value="GO">Goiás</option>
					                    <option value="ES">Espírito Santo</option>
					                    <option value="MA">Maranhão</option>
					                    <option value="MT">Mato Grosso</option>
					                    <option value="MS">Mato Grosso do Sul</option>
					                    <option value="MG">Minas Gerais</option>
					                    <option value="PA">Pará</option>
					                    <option value="PB">Paraiba</option>
					                    <option value="PR">Paraná</option>
					                    <option value="PE">Pernambuco</option>
					                    <option value="PI">Piauí­</option>
					                    <option value="RJ">Rio de Janeiro</option>
					                    <option value="RN">Rio Grande do Norte</option>
					                    <option value="RS">Rio Grande do Sul</option>
					                    <option value="RO">Rondônia</option>
					                    <option value="RR">Roraima</option>
					                    <option value="SP">São Paulo</option>
					                    <option value="SC">Santa Catarina</option>
					                    <option value="SE">Sergipe</option>
					                    <option value="TO">Tocantins</option>
					                    </select>
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Cidade:</div>
					                    <input id="inputCidade" name="cidade" type="text" placeholder="Cidade"
					                        class="form-control with-placeholder" />
					                </div>
					            </div>

					        </div>
					        <div class="form-group">
					            <div class="placeholder">Complemento:</div>
					            <textarea id="adicionalInfo" name="complemento" rows="5" placeholder="Complemento"
					                class="form-control with-placeholder"></textarea>
					        </div>

					        <?php
					if($sub_menu_active=="vendedores"){ ?>
					        <hr />
					        <h3>Parâmetros do Vendedor (Porcentagem % )</h3>

					        <div class="row">
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Honorários adimplência:</div>
					                    <input id="inputHonorAdimp" name="honor_adimp" type="text" placeholder="Honorários adimplência"
					                        class="form-control with-placeholder" />
					                </div>
					            </div>
					            <div class="col-md-6">
					                <div class="form-group">
					                    <div class="placeholder">Honorários inadimplência:</div>
					                    <input id="inputHonorInadimp" type="text" name="honor_inadimp"
					                        placeholder="Honorários inadimplência" class="form-control with-placeholder" />
					                </div>
					            </div>

					        </div>
					        <?php } ?>

					        <?php
					if($sub_menu_active=="vendedores" || $sub_menu_active=="compradores"){ ?>
					        <hr />
					        <h3>Haras <a href="<?php echo $link."/haras";?>" target="_blank" class="pull-right fs-20 green_light"><i
					                    class="fa fa-plus-square fs-20" aria-hidden="true"></i> Cadastrar Haras</a> </h3>

					        <div id="row_status" class="row">
					            <div id="quadro_haras" class="col-md-12 hidden">
					                <div class="row">
					                    <div id="info_haras" class="col-xs-12">
					                    </div>
					                </div>
					                <br />
					            </div>
					            <div id="select_haras" class="col-md-12">
					                <div class="form-group input-icon right">
					                    <div class="placeholder">Haras:</div>
					                    <input id="inputHaras" name="findharas" type="text" placeholder="Haras"
					                        class="form-control  with-placeholder" autocomplete="off" onkeyup="busca_haras();" />
					                    <input id="inputHarasId" name="haras_id" type="hidden" />
					                    <div id="autocp_haras" class="hidden autocp_div">
					                        <div id="div_loading_autocp" class="row loading_something hidden ">
					                            <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
					                        </div>
					                    </div>
					                </div>

					            </div>
					        </div>
					        <?php } ?>

					    </div>

					    <button type="submit" class="hidden"></button>
					</form>
					<form id="formulario" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-sm-12 text-center">
							<input name="arquivo" id='arquivo' type="file" title="Anexar documento" class='hidden'/>
							<input name="nomearquivo" id='nomearquivo' type="text" class='hidden'/>
							<label for="arquivo" style="cursor: pointer;color:#006; "><h4>Anexar documento</h4></label>
							<button id="btnarquivo" class='btn hidden'>Enviar</button>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-1 text-center"></div>
						<div class="col-sm-10 text-center documentos" style='border:solid 1px #ccc'>
							
						</div>
						<div class="col-sm-1 text-center"></div>
					</div>
					</form>
					<script>
function ver_arquivos(){
	if ($('#inputCpfCnpj').val() != ''){
		$.ajax({
			url: '<?php echo $link?>/adm/pessoas/check_documentos.php',
			method: 'POST',
			data: {
				pasta: $('#inputCpfCnpj').val()
			},
			success: function(data) {
				data = JSON.parse(data);
				var arquivos = '';
				if (data.status == 1){
					if (data.arquivos){
						data.arquivos.forEach(element => {
							if (element != '.' && element != '..'){
								arquivos += `
								<hr>
								<div class='row'>
									<div class='col-sm-2'>
										<button class='btn btn-link' onclick='deleteFile("/documentos/pessoas/${$('#inputCpfCnpj').val()}/${element}")'><i class='fa fa-minus-circle'/></button>
									</div>
									<div class='col-sm-10'>
										<a target="_blank" href='<?php echo $link?>/documentos/pessoas/${$('#inputCpfCnpj').val()}/${element}'>${element}</a>
									</div>
								</div>
								`;
							}
						});
					}
				}
				$('.documentos').html(arquivos+'<hr>');
			}
		});
	} else {
		$('.documentos').html('');
	}
}

function deleteFile(arquivo){
	event.preventDefault();
	$.ajax({
		url: '<?php echo $link?>/adm/pessoas/delete_documentos.php',
		method: 'POST',
		data: {
			arquivo: arquivo
		},
		success: function(data) {
			jAlert('Documento removido!','Bom trabalho!','ok');
			ver_arquivos();
		}
	});
}
$('#arquivo').change(function(){
	$("#btnarquivo").click();	
});

$('#inputCpfCnpj').change(function(){
	ver_arquivos();
});

$("#formulario").submit(function() {
	event.preventDefault();
	if ($('#inputCpfCnpj').val() == '') {
		jAlert('Antes de efetuar o upload, preencha todos os dados do cadastro.','Ooops','alert');
	} else {
		$('#nomearquivo').val($('#inputCpfCnpj').val());
		var formData = new FormData(this);
		$.ajax({
			url: '<?php echo $link?>/adm/pessoas/upload_documentos.php',
			type: 'POST',
			data: formData,
			success: function(data) {
				if (data == 'ok'){
					jAlert('Upload finalizado!','Bom trabalho!','ok');
					ver_arquivos();
				}
				else
					jAlert('Erro ao carregar o documento!','Oops!','alert');
			},
			cache: false,
			contentType: false,
			processData: false,
			xhr: function() { // Custom XMLHttpRequest
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
					myXhr.upload.addEventListener('progress', function() {
						/* faz alguma coisa durante o progresso do upload */
					}, false);
				}
				return myXhr;
			}
		});
	}
});
//  VERIFICA SE JÁ EXISTE PESSOA COM EMAIL OU DOCUMENTO PREENCHIDO

function verifica_email_doc_existente(campo) {
    if (campo == 'doc') {
        valor = $('#inputCpfCnpj').val();
    } else if (campo == 'email') {
        valor = $('#inputEmail').val();
    }
    id = $('#inputId').val();
    $.getJSON('<?php echo $link."/repositories/pessoas/pessoas.ctrl.php?acao=verifica_email_doc_existente";?>', {
        campo: campo,
        valor: valor,
        id: id,
        ajax: 'true'
    }, function(j) {

        if (j == 'doc_invalido') {
            msg = 'CPF/CNPJ inválido';
            jAlert(msg, 'Oops!', 'alert');
            return 2;
        } else if (j > 0) {
            if (campo == 'doc') {
                $('#inputCpfCnpj').val('');
                msg = 'Este documento já está salvo para outro usuário.';
            } else if (campo == 'email') {
                $('#inputEmail').val('');
                msg = 'Este e-mail já está salvo para outro usuário.';
            }
            jAlert(msg, 'Oops!', 'alert');
            return 2;
        } else {
            if (campo == 'doc') {
                salvarFormulario(1);
            } else if (campo == 'email') {
                salvarFormulario(2);
            }

        }
    });
}



/*  AJAX HARAS */

function busca_haras() {
    palavra = $('#inputHaras').val();
    tam_palavra = palavra.length;
    if (tam_palavra >= 3) {
        $('#autocp_haras').removeClass('hidden');
        $('.loading_something').removeClass('hidden');
        if (delay_busca) {
            clearTimeout(delay_busca);
        }
        delay_busca = setTimeout(function() {
            $.get("<?php echo $link."/adm/haras/lista_haras_autocomplete.ajax.php";?>", {
                palavra: palavra
            }, function(result) {
                if (result == '0') {
                    //limpa resultados div_loading
                    $('.autocp_div').html(div_loading_autocp);
                    $('.autocp_div').addClass('hidden');
                    click_condominos_control = 0;
                } else {
                    //exibe resultados
                    $('#autocp_haras').html(result);
                    $('#autocp_haras').removeClass('hidden');
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

function escolhe_autocomplete_haras(haras) {
    $('#inputHarasId').val(haras.id);

    btn_limpa_haras = "";
    btn_limpa_haras = '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_haras();"></span>';

    haras = btn_limpa_haras + haras.nome;
    if (haras.telefone != null)
        haras += "<br>" + haras.telefone;
    $("#info_haras").html(haras);


    $('#autocp_haras').addClass('hidden');
    $('#select_haras').addClass('hidden');
    $('#quadro_haras').removeClass('hidden');
}

function limpa_haras() {
    $('#inputHarasId').val('');
    $('#inputHaras').val('');
    $('#quadro_haras').addClass('hidden');
    $('#select_haras').removeClass('hidden');
}
					</script>