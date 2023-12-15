<?php

//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once($raiz."/inc/util.php");


$tipos_lançamentos = array( '(+) CRÉDITO - PAGAMENTO DE PARCELA DE COMPRA',
							'(+) CRÉDITO - REEMBOLSO',
							'(+) CRÉDITO - OUTROS',
							'(-) DÉBITO - ANTECIPAÇÃO DE REPASSE',
							'(-) DÉBITO - TAXAS BANCÁRIAS',
							'(-) DÉBITO - PAGAMENTO DE PARCELA DE COMPRA',
							'(-) DÉBITO - HONORÁRIOS',
							'(-) DÉBITO - DILIGÊNCIAS',
							'(-) DÉBITO - OUTROS',
							);
					

$tedsDB  = new tedsDB();
$ted     = new teds();
$ted->pessoas_id_vendedor = $id;

$filtros="";
if($ehCliente){
	//$filtros = array('filtro_ativo'=>1);
}


?>
<?php if( consultaPermissao($ck_mksist_permissao,"cad_teds","editar")){ ?> 

<button id="btn-nted" class="btn btn-success" onclick="f_div_nova_ted(1);"> Nova TED </button>
<div id="div_nova_ted" class="hidden">
	<h3 class="mg-tp-0">Nova TED </h3>
    
    <h4>Selecionar parcelas para transferir valor</h4>
    <form id="form_ted_parcelas"  >
    <table class="table table-striped table-condensed">
    <thead>
    <th>
    <input id="check_parc_all" type="checkbox" onclick="javascript:sel_all();" />
    </th>
    <th>Parcela</th>
    <th class="hidden-xs hidden-sm">Vencimento</th>
    <th class="hidden-xs hidden-sm">Crédito</th>
    <th class="hidden-xs hidden-sm">Vl Parcela</th>
    <th class="hidden-xs hidden-sm">Valor Pago</th>
    <th class="hidden-xs hidden-sm">Honor %</th>
    <th class="hidden-xs hidden-sm">Transferir</th>
    </thead>
    <tbody>
    <?php
	$parcelas_teds = $tedsDB->lista_parcelas_teds($ted, $conexao_BD_1,  $id  ,  "t.id desc," ,  0,"N");
	$count_parcs = 0;
	foreach($parcelas_teds as $parcela){ 
		$count_parcs++;
		
		$dt_venc = $parcela['dt_vencimento'];
		if(!empty($parcela['dt_vencimento_original']) && $parcela['dt_vencimento_original']!= "0000-00-00")
			$dt_venc = $parcela['dt_vencimento_original'];
	?>
    	<tr <?php if(!empty($parcela['tratar_ted'])) echo 'class="danger danger_row"';?>>
            <td>
            	<?php
				//valor da parcela
				$valor_parcela = $parcela['vl_pagto'];
				// honorários
				$vl_honorarios = $parcela['vl_honorarios'];
				
				$vl_transferir = str_replace(',', ".",$valor_parcela)-str_replace( ',', ".",$vl_honorarios);
				
				?>
            
                <input id="check_parc_<?php echo $parcela['id'];?>" name="pc_<?php echo $parcela['id'];?>" class="check_parc" type="checkbox" onclick="javascript:soma_parcelas_check(<?php echo $parcela['id'];?>, <?php echo str_replace( ',', ".", $vl_transferir); ?>);"  />
            </td>
            <td>
                <?php echo "Parcela ".$parcela['nu_parcela']." do Contrato ".$parcela['c_id'];?>
                <div  class="visible-xs visible-sm">
                	<?php  echo 'Vencimento: '.ConverteData($dt_venc);?>
                    <?php echo '<br>Pagto: '.ConverteData($parcela['dt_credito']); ?>
                    <?php echo '<br>Valor: '.Format($parcela['vl_corrigido'],'numero');?>
                    <?php echo '<br>Vl Pago: '.Format($parcela['vl_pagto'],'numero');?>
                    <?php echo '<br>Honor.: '.Format($vl_honorarios,'numero')." (".$parcela['honor_adimp']."%)";?>
                    <?php echo '<br> Vl Tranf.: '.Format($vl_transferir,'numero');?>
                </div>
            </td>
            <td  class="hidden-xs hidden-sm">
                <?php  echo ConverteData($dt_venc);?>
            </td>
            <td  class="hidden-xs hidden-sm">
                <?php echo ConverteData($parcela['dt_credito']); ?>
            </td>
            <td  class="hidden-xs hidden-sm">
                <?php echo Format($parcela['vl_corrigido'],'numero');?>
            </td>
            <td  class="hidden-xs hidden-sm">
                <?php echo Format($parcela['vl_pagto'],'numero');?>
            </td>
            <td class="hidden-xs hidden-sm">
                <?php echo Format($vl_honorarios,'numero')." (".$parcela['honor_adimp']."%)";?>
            </td>
            <td class="hidden-xs hidden-sm">
                <?php echo Format($vl_transferir,'numero');?>
            </td>
            
    	</tr>
	
	<?php }
	if(!$count_parcs){
		echo '<tr><td colspan="15">Nenhuma parcela com pagamento pendente.</td></tr>';
	}
	 ?> 
    </tbody>
    </table>
    </form>
    <form id="form_ted" action="javascript:salvar_ted(0);">
    <h4>Valor das Parcelas R$ <span id="vl_parcelas"></span></h4>
    
    <h4>Lançamentos  <i class="fa fa-plus-circle pointer green_light fs-21" onclick="add_lancamento();"></i> </h4>
    <div id="div_lancamentos">
            <div id="html_lancamentos" > 
            </div>

    </div>
    <div class="cb"><br /></div>
    <h4>Valor dos Lançamentos R$ <span id="vl_lancamentos"></span></h4>
    
    <h4>Valor da TED R$ <span class="vl_ted"></span></h4>
    
    
    <h3>Dados da TED</h3>
    
    
    <div class="row">
   	 <div class="col-md-3">
    	<div class="form-group input-icon right">
    	Valor da TED:<br />
    	R$ <span class="vl_ted fs-21"></span>
    	<input type="hidden" id="ted_vl" name="ted_vl"   class="form-control "/>
        </div>
      </div>
    </div>
    
    <div class="row">
   	 <div class="col-md-3">
    	<div class="form-group input-icon right">
        Data do agendamento:<br />
    	<input type="text" id="ted_dt" name="ted_dt"  class="form-control "/>
        </div>
      </div>
    </div>
    
    <?php
	$domicilios = $tedsDB->lista_domicilios_teds($id, $conexao_BD_1);
	$count_domc = count($domicilios);
	if($count_domc){?>
		<div class="row">
   	 		<div class="col-md-8">
    			<div class="form-group input-icon right">
        Domicílios prévios deste cliente:<br />
        <select id="domicilios_prev" class="form-control " onchange="troca_domicilio();" style="display:inline; width:90%">
        <option value="">Selecionar</option>
		<?php
        foreach($domicilios as $domicilio){  ?>
        <option value="<?php echo $domicilio['banco']."-".$domicilio['agencia']."-".$domicilio['dv_agencia']."-".$domicilio['conta']."-".$domicilio['dv_conta'];?>">
        <?php echo 'Banco: '.$domicilio['banco'];
			  echo" Agência: ".$domicilio['agencia']."-";
			  echo $domicilio['dv_agencia'];
			  echo " Conta: ".$domicilio['conta']."-";
			  echo $domicilio['dv_conta'];
			  
		?>
        </option>
        <?php
		}
		?>
        </select>
        
        <div id="bt_del_domc" class="pointer hidden" onclick="del_domc_bancario();" style="float:right; width:20px;">
        <i class=" fa fa-times fs-21 red_light"></i>
        </div>
        
        </div>
      </div>
    </div>
	<?php
    }
	?>
    
    <div class="row">
   	 <div class="col-md-3">
    	<div class="form-group input-icon right">Código do Banco:<br />
    	<input type="number" id="ted_bc"  id="ted_bc" class="form-control "/>
        </div>
      </div>
    </div>
    
    <div class="row">
   	 <div class="col-md-3">
    	<div class="form-group ">Agência + dígito:<br />
   		 <input type="number" id="ted_ag"  id="ted_ag" class="form-control fl-lf wd-150 mg-rg-20"/> 
         <input type="text" id="ted_ag_dig"  id="ted_ag_dig" class="form-control wd-60 fl-lf" maxlength="1"/>
        </div>
      </div>
    </div>
    
    <div class="row">
   	 <div class="col-md-3">
    	<div class="form-group ">Conta + dígito:<br />
    		<input type="number" id="ted_cc"  id="ted_cc" class="form-control fl-lf  wd-150 mg-rg-20"/> 
            <input type="text" id="ted_cc_dig"  id="ted_cc_dig" class="form-control  wd-60 fl-lf" maxlength="1"/>
        </div>
      </div>
    </div>
    
    <br />
    <button id="btn-sav-ted" type="submit" class="btn btn-success">Salvar TED</button>
    <button type="button" class="btn btn-warning" onclick="f_div_nova_ted(0);">Cancelar TED</button>
    
    </form>
    <hr />
    
</div>

<?php }  ?> 
<h3 class="mg-tp-10">TEDs </h3>


<table class="table table-bordered">
<thead>
<th>ID</th>
<th>TED</th>
<th class="hidden-xs hidden-sm">Agendada p/</th>
<th class="hidden-xs hidden-sm">Valor</th>
<th class="hidden-xs hidden-sm">Banco</th>
<th class="hidden-xs hidden-sm">Agência</th>
<th class="hidden-xs hidden-sm">Conta</th>
<th class="hidden-xs hidden-sm">Status</th>
<?php if( consultaPermissao($ck_mksist_permissao,"cad_teds","editar")){ ?> 
<th>Ação</th>
<?php } ?> 
</thead>
<tbody>
<?php
$teds_vendedor = $tedsDB->lista_teds($ted, $conexao_BD_1,  $filtros  ,  "t.id desc," ,  0,"N");
$count_teds = 0;
foreach($teds_vendedor as $ted_item){ 
	$count_teds++;
	$status_ted = '';
	switch ($ted_item['status_ted']) {
		case 1: 
			$status_ted =  'Aguardando Envio p/ banco  '.$ted_item['arquivos_id_remessa']; 
			if(!empty($ted_item['pessoas_id_envio']))
				$status_ted =  'Aguardando Retorno '; 
			
			break;
		case 2: $status_ted =  'Agendada';  break;
		case 3: $status_ted =  'Confirmada';  break;
		case 4: $status_ted =  'Corrompida';  break;
	}
	
?>
	<tr id="tr_ted_<?php echo $ted_item['id']; ?>">
    <td><?php echo $ted_item['id']; ?></td>
    <td>
		<?php echo 'Cadastrada '.ConverteData($ted_item['dt_inclusao']); ?>
        <div  class="visible-xs visible-sm">
        <?php echo 'Agendada p/'.ConverteData($ted_item['dt_ted']); ?>
        <?php echo '<br>R$ '.Format($ted_item['vl_ted'],'numero'); ?>
        <?php echo '<br>Banco '.$ted_item['banco']; ?>
        <?php echo '<br>Agência '.$ted_item['agencia']."-"; echo $ted_item['dv_agencia']; ?>
        <?php echo '<br>Conta '.$ted_item['conta']."-"; echo $ted_item['dv_conta']; ?>
        <?php echo '<br>'.$status_ted; ?>
        </div>
    
    </td>
    <td class="hidden-xs hidden-sm"><?php echo ConverteData($ted_item['dt_ted']); ?></td>
    <td class="hidden-xs hidden-sm"><?php echo 'R$ '.Format($ted_item['vl_ted'],'numero'); ?></td>
    <td class="hidden-xs hidden-sm"><?php echo $ted_item['banco']; ?></td>
    <td class="hidden-xs hidden-sm"><?php echo $ted_item['agencia']."-"; echo $ted_item['dv_agencia']; ?></td>
    <td class="hidden-xs hidden-sm"><?php echo $ted_item['conta']."-"; echo $ted_item['dv_conta']; ?></td>
    <td class="hidden-xs hidden-sm"><?php  echo $status_ted; ?></td>
    
    <?php if( consultaPermissao($ck_mksist_permissao,"cad_teds","editar")){ ?> 
    	<td> 
		<i class="fa fa-trash red_light pointer fs-21" onclick="remove_ted(<?php echo $ted_item['id']; ?>,<?php echo $ted_item['status_ted']; ?>);"></i>
        </td>
	<?php	 
	}
	?>
    </tr>


<?php }
if(!$count_teds){
	echo '<tr><td colspan="10">Nenhuma TED cadastrada.</td></tr>';
}
 ?> 
</tbody>
</table>

<script>
 
 	function get_html_lancamentos(cont_lancamento){ 
		html_lancamentos = '<div id="html_lancamentos" > '+
                '<div class="col-xs-12 col-sm-2  pd-lr-2  pd-tp-10">'+
                   ' <div class="placeholder">Valor:</div>'+
                   ' <input id="inputLcValor'+cont_lancamento+'" name="inputLcValor" type="text" placeholder="Valor" class="form-control with-placeholder inputLcValor"  onkeyup="javascript:somar_lancamentos();" >  '  +
                '</div>'+
                '<div class="col-xs-12 col-sm-2  pd-lr-2 pd-tp-10">'+
                    '<div class="placeholder">Tipo Lançamento:</div>'+
                   ' <select id="inputLcTipo'+cont_lancamento+'" name="inputLcTipo" class="form-control with-placeholder" onchange="javascript:selecionou_tipo_lancamento('+cont_lancamento+');"  >  '+
                   ' <option value="">Selecione</option>'+
					<?php foreach($tipos_lançamentos as $tipo_lançamento){ ?>
					'<option value="<?php echo $tipo_lançamento;?>"><?php echo $tipo_lançamento;?></option>'+	
					<?php
                    }
					?>
                    '</select>                      '+
                '</div>'+
                '<div class="col-xs-12 col-sm-8  pd-lr-2 pd-tp-10">'+
                    '<div class="placeholder">Observação:</div>'+
                    '<input id="inputLcObs" name="inputLcObs" type="text" placeholder="Observação" class="form-control with-placeholder" value="" >     '+                   
               ' </div>'+
            '</div>';
			return html_lancamentos;
	 }
	 
 function troca_domicilio(){
	 
	$('#bt_del_domc').addClass('hidden');
	domicilio = $('#domicilios_prev').val();
	if(domicilio==""){
		$('#ted_bc').val('');
		$('#ted_ag').val('');
		$('#ted_cc').val('');
		$('#ted_ag_dig').val('');
		$('#ted_cc_dig').val('');
	}
	else{
		arr_domc = domicilio.split("-");
		$('#ted_bc').val(arr_domc[0]);
		$('#ted_ag').val(arr_domc[1]);
		$('#ted_ag_dig').val(arr_domc[2]);
		$('#ted_cc').val(arr_domc[3]);
		$('#ted_cc_dig').val(arr_domc[4]);
		$('#bt_del_domc').removeClass('hidden');
	}
}

 function del_domc_bancario(confirma){
	jConfirm('Deseja remover o domicílio bancário? Ele não aparecerá mais como opção para as próximas TEDS.', 'Remover esta conta?', function(r) {
			if(r){
				domicilio = $('#domicilios_prev').val();
				arr_domc = domicilio.split("-");
				
				$('#domicilios_prev').val('');
				$('#ted_bc').val('');
				$('#ted_ag').val('');
				$('#ted_cc').val('');
				$('#ted_ag_dig').val('');
				$('#ted_cc_dig').val('');
				$('#bt_del_domc').addClass('hidden');
				$("#domicilios_prev option[value='"+domicilio+"']").remove();
				
				
				$.getJSON("<?php echo $link."/repositories/teds/teds.ctrl.php?acao=del_domc";?>", {
					 domicilio:domicilio
				}, function(result){
			 });
				
				
			}
			else{
				jAlert('As informações estão seguras.','Ação Cancelada','ok');
			}
		});
 }
 
 function f_div_nova_ted(acao){
	if(acao==1){
		$('#btn-nted').addClass('hidden');
		$('#div_nova_ted').removeClass('hidden');	
	}
	else{
		//cancela ted
		$('#domicilios_prev').val(''); 
		
		$('#ted_dt').val('');
		$('#ted_bc').val('');
		$('#ted_ag').val('');
		$('#ted_cc').val('');
		
		$('#ted_cc_dig').val('');
		$('#ted_ag_dig').val('');
		
		$('#div_nova_ted').addClass('hidden');
		$('#btn-nted').removeClass('hidden');
	}
 }
 
 
 var soma_parcelas    = 0;
 var soma_lancamentos = 0;
 var valor_ted 		= 0;
 var html_lancamentos = '';
 
 
 function soma_parcelas_check(id, valor){
	 
	if ( $('#check_parc_' + id).is(":checked")) {
		soma_parcelas = parseFloat(parseFloat(soma_parcelas) + parseFloat(valor)).toFixed(2);
	}
	else {
		soma_parcelas = parseFloat(soma_parcelas - valor).toFixed(2);
	}
	atualiza_valor_ted();
 }
 
 function sel_all(){
	$('.check_parc').prop('checked', false);
	if ( $('#check_parc_all').is(":checked")) {
		//seleciona todos
		soma_parcelas=0;
		 $('.check_parc').click();
	}
	else {
		//desmarca 
		soma_parcelas=0;
	}
	atualiza_valor_ted();
 }
 
 
 var cont_lancamento = 0;
 function add_lancamento(){
	 cont_lancamento++;
	html_lancamentos = get_html_lancamentos(cont_lancamento);
	//alert('add lancamentos: '+html_lancamentos);
	$('#div_lancamentos').append(html_lancamentos);
	$('.inputLcValor').maskMoney({allowZero:true, allowNegative:true});
 }
 
 function somar_lancamentos(){
	soma_lancamentos = 0;
	$(".inputLcValor").each(function() {
		valor = $(this).val();
		if($.isNumeric(valor ))
			soma_lancamentos = parseFloat(parseFloat(soma_lancamentos) + parseFloat(valor)).toFixed(2);
	});	
	atualiza_valor_ted();
 }
 
 function atualiza_valor_ted(){
	$('#vl_parcelas').html(soma_parcelas);
	$('#vl_lancamentos').html(soma_lancamentos);
	valor_ted = parseFloat(parseFloat(soma_lancamentos) + parseFloat(soma_parcelas)).toFixed(2);
	$('.vl_ted').html(valor_ted);
	$('#ted_vl').val(valor_ted);
	//alert(soma_parcelas+" + "+soma_lancamentos+" = "+valor_ted);
 }
 
 function salvar_ted(confirma){
	 valor_ted = $('#ted_vl').val();
	 data_ted = $('#ted_dt').val();
	 banco_ted = $('#ted_bc').val();
	 agencia_ted = $('#ted_ag').val();
	 agencia_dig = $('#ted_ag_dig').val();
	 conta_ted = $('#ted_cc').val();
	 conta_dig = $('#ted_cc_dig').val();
	 
	 if( !$.isNumeric(valor_ted )  || valor_ted <0){
	 	jAlert('Valor inválido para TED: '+valor_ted,'Oops');
	 }
	 else if( banco_ted =='' || !(banco_ted>0)){
	 	jAlert('Preencha o código do Banco '+banco_ted,'Oops');
	 }
	 else if( agencia_ted =='' || !(agencia_ted>0)){
	 	jAlert('Preencha a agência: '+agencia_ted,'Oops');
	 }
	 else if( conta_ted =='' || !(conta_ted>0)){
	 	jAlert('Preencha a Conta: '+conta_ted,'Oops');
	 }
	 else if( agencia_dig ==''){
	 	jAlert('Preencha o dígito verificador da agência.','Oops');
	 }
	 else if( conta_dig =='' ){
	 	jAlert('Preencha o dígito verificador da conta.','Oops');
	 }
	 else{
		 if(confirma==0){
			 	msg_confirma_ted = 'Confirma TED agendada para '+data_ted+'<br> no valor de R$ '+valor_ted+' <br> para Banco '+banco_ted+' Agência '+agencia_ted+'-'+agencia_dig+' e Conta '+conta_ted+'-'+conta_dig+'?';
				if(valor_ted == 0 || valor_ted == '0.00' ){
					msg_confirma_ted = 'Confirma TED com valor Zerado? Não será gerado arquivo para esta TED e as parcelas relacionadas serão todas marcadas como já repassadas para o cliente.';
				}
				jConfirm(msg_confirma_ted, 'Confirmar TED?', function(r) {
				if(r){
					salvar_ted(1);
				}
				else{
					 $('#ted_dt').val('');
					 $('#ted_bc').val('');
					 $('#ted_ag').val('');
					 $('#ted_cc').val('');
					 $('#ted_cc_dig').val('');
					 $('#ted_ag_dig').val('');
					 $('#domicilios_prev').val(''); 
					jAlert('As informações estão seguras.','TED Cancelada','ok');
				}
			});
		  
		 }
		 else{
			ted_form = $('#form_ted').serializeArray();
			form_ted_parcelas = 'todas_parcelas';
			//verifica se tem alguma não selecionada
			todos_selecionados = 'sim';
			$(".check_parc").each(function(){
				if ($(this).prop('checked')==false){ 
					todos_selecionados = 'nao'; 
				} 
			});
			if(todos_selecionados == 'nao'){
				form_ted_parcelas = $('#form_ted_parcelas').serializeArray();
			}
			//alert(JSON.stringify(ted_form));
			$('#btn-sav-ted').addClass('hidden');
			$.getJSON("<?php echo $link."/repositories/teds/teds.ctrl.php?acao=nova_ted";?>", {
					 valor_ted:valor_ted,
					 data_ted:data_ted,
					 banco_ted:banco_ted,
					 agencia_ted:agencia_ted,
					 conta_ted:conta_ted,
					 ted_form:ted_form,
					 agencia_dig:agencia_dig,
					 conta_dig:conta_dig,
					 form_ted_parcelas:form_ted_parcelas,
					 id_vend:<?php echo $id;?>
				}, function(result){
				if( result.status>0 ){	
					//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
					jAlert(result.msg,'Bom trabalho!','ok');
					$('#popup_ok').on( "click", function() {
						 location.reload();
					});
				}
				else{
					$('#btn-sav-ted').removeClass('hidden');
					jAlert(result.msg,'Não foi possível salvar as informações!','alert');
				}
			 });
		}
	}
 } 
 
 
 
 
 function remove_ted(ted_id,status){
	
	
	msg_confirm = 'Ao remover uma TED serão removidos também todos os relacionamentos com as parcelas desta. Caso a TED já tenha sido confirmada e enviada para o cliente isso pode gerar uma divergência entre os dados do sistema e seu extrato bancário, logo esta requisição será armazenada para uma possível auditoria futura.<br>As informações desta TED não poderão ser recuperadas.';
	jConfirm(msg_confirm, 'Remover TED?', function(r) {
		if(r){
				$.getJSON("<?php echo $link."/repositories/teds/teds.ctrl.php?acao=del_ted";?>", {
					 ted_id:ted_id
					}, function(result){
							if( result.status>0 ){	
								//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
								jAlert(result.msg,'Bom trabalho!','ok');
								$('#tr_ted_'+ted_id).remove();
							}
							else{
								jAlert(result.msg,'Não foi possível salvar as informações!','alert');
							}
				 });
			}
			else{
				jAlert('As informações estão seguras.','Ação Cancelada','ok');
			}
			});
 }
 
 function selecionou_tipo_lancamento(cont){
	tipo = $('#inputLcTipo'+cont).val();
	valor = $('#inputLcValor'+cont).val();
	
	if(tipo.substr(0,3) == '(+)'){
		if(valor<0){
			valor = valor*-1;
			$('#inputLcValor'+cont).val(valor);
		}
	}
	else if(tipo.substr(0,3) == '(-)'){
		if(valor>0){
			valor = valor*-1;
			$('#inputLcValor'+cont).val(valor);
		}
	}	
	else{
		jAlert('Selecione um tipo de Lançamento','Oops');
	}
	somar_lancamentos();
	
 }
 </script>