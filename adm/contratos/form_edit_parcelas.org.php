<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");

$msg = array();
$contratosDB = new contratosDB();
$contratos   = new contratos();

$contratos->id = $_REQUEST['contrato_id'];
if (array_key_exists('cadastro',$_REQUEST))
    $cadastrando = ($_REQUEST['cadastro'] === 'true');
else
    $cadastrando = false;



$info_contrato = $contratosDB->lista_contratos($contratos, $conexao_BD_1);

//echo "<pre>";
//print_r($info_contrato);
//echo "</pre>";

$total 			 = $info_contrato[0]["vl_contrato"];
$status_contrato = $info_contrato[0]["status"];
$tipo_contrato   = $info_contrato[0]["tp_contrato"];
$juros			 = $info_contrato[0]["juros"];
$honor_inadimp	 = $info_contrato[0]["honor_inadimp"];
$parcela_primeiro_pagto = $info_contrato[0]["parcela_primeiro_pagto"];
$gerar_boleto    = $info_contrato[0]["gerar_boleto"];
$motivo_zerado   = $info_contrato[0]["motivo_zerado"];
$pc_total        = $info_contrato[0]["pc_total"];
$pc_liqd         = $info_contrato[0]["pc_liqd"];
$arquivo_id      = $info_contrato[0]["arquivo_id"];
$dt_correcao_monetaria = $info_contrato[0]["dt_atualizacao_monetaria"];

$contrato_liquidado = false;
if ($pc_liqd == $pc_total){
    $contrato_liquidado=true;
}

$class_hidden  = "";
$class_juros_multa	 	 = "";
if (($status_contrato == "pendente") && (($tipo_contrato == "adimplencia" ))) {
	$class_juros_multa = " hidden"; 
	$class_hidden  = "hidden";
}

$readonly_edit_vl_parcela 	 = " readonly ";
$readonly_edit_dt_vencimento = " readonly ";
$readonly_edit_dt_pagamento  = " readonly ";
$readonly_edit_vl_juros 	 = " readonly ";
$readonly_edit_vl_honorarios = " readonly ";
$readonly_edit_nu_parc_acordo= " readonly ";

$class_hidden_simulacao = "";


if ($status_contrato == "confirmado"){
	$class_hidden  = "hidden";
}
elseif (($status_contrato == "em_acordo")||($status_contrato == "parcialmente_em_acordo")||($status_contrato == "virou_inadimplente")||($status_contrato == "acao_judicial")){
	$class_hidden_simulacao = "hidden";
	if ($status_contrato == "virou_inadimplente"){
		$class_hidden  = "hidden";
	}
}
elseif ($status_contrato == "pendente") {
	$readonly_edit_vl_parcela 	 = "";
	$readonly_edit_dt_vencimento = "";
	$readonly_edit_dt_pagamento  = "";
	$readonly_edit_vl_juros 	 = "";
	$readonly_edit_vl_honorarios = "";
	$readonly_edit_nu_parc_acordo= "";
}

$class_gerar_boleto = "";
if (($gerar_boleto == "N")||($status_contrato == "pendente")||($arquivo_id=="")){
    $class_gerar_boleto = "hidden";
}


$parcelas = $contratosDB->lista_parcelas_contratos($contratos->id, $conexao_BD_1);
?>

<div class="row">
    <div class="col-xs-12 col-sm-7">
        <form id="form_atualiza_simulacao" class="<?php echo $class_hidden?>">
            <input id="inputIdSimulacao" type="hidden" name="id" placeholder="Id" class="form-control"
                value="<?php echo $contratos->id; ?>" />
            <input id="inputStatusContrato" type="hidden" name="status_contrato" class="form-control"
                value="<?php echo $status_contrato; ?>" />
            <div class="row">
                <div class="col-xs-12">
                    <h4>Encargos</h4>
                    <div class="row">

                        <div class="col-xs-12 col-sm-3">
                            <div class="placeholder">Juros(%):</div>
                            <input id="inputJurosSimulacao" name="juros" type="text" placeholder="Juros"
                                class="form-control with-placeholder" value="<?php echo $juros;?>"
                                <?php echo $readonly_edit_vl_juros;?>
                                onchange="javascript:$('#buttonSimulacao').click();" />
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="placeholder">Honorários(%):</div>
                            <input id="inputHonorInadimpSimulacao" name="honor_inadimp" type="text"
                                placeholder="Honorários" class="form-control with-placeholder"
                                value="<?php echo $honor_inadimp;?>" <?php echo $readonly_edit_vl_honorarios;?>
                                onchange="javascript:$('#buttonSimulacao').click();" />
                        </div>

                        <div class="col-xs-12 col-sm-6 <?php echo $class_hidden?>">
                            <button id="buttonSimulacao" name="button_simulacao" type="button"
                                class="btn btn-sm btn-primary <?php echo $class_hidden_simulacao;?> "
                                onClick="javascript:atualiza_simulacao(<?php echo $contratos->id; ?>)">Atualiza
                                simulação</button>
                            <a href="<?php echo $link.'/inc/pdf/gera_pdf_simulacao.php?id='.$contratos->id; ?>"
                                target="_blank"
                                class="btn  btn-sm btn-info <?php echo $class_hidden_simulacao;?> ">PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <form id="form_gera_acordo" class="<?php echo $class_hidden?>">
        <div class="col-xs-12 col-sm-3 div_form_gera_acordo ">

            <input id="inputIdAcordo" type="hidden" name="id" class="form-control"
                value="<?php echo $contratos->id; ?>" />
            <input id="inputVlAcordo" type="hidden" name="vl_acordo" class="form-control" />
            <div class="row <?php echo $class_hidden; ?>">
                <div class="col-xs-12 " id="soma_original"></div>
                <div class="col-xs-12 " id="soma_correcao"></div>
                <div class="col-xs-12 " id="soma_juros"></div>
                <div class="col-xs-12 " id="soma_honorarios"></div>
                <div class="col-xs-12 " id="soma_corrigido"></div>
                <div class="col-xs-12 " id="data_at_mon"></div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-2 div_form_gera_acordo ">
            <div class="row">
                <div class="col-xs-12 col-sm-12  <?php echo $class_hidden_simulacao;?>">
                    <div class="placeholder">Nº Parcelas do acordo:</div>
                    <input id="inputQtParcelasAcordo" name="qt_parcelas_acordo" type="number"
                        placeholder="Nº Parcelas do Acordo" class="form-control with-placeholder "
                        <?php echo $readonly_edit_nu_parc_acordo;?> />
                </div>
            </div>
            <div class="row mg-tp-10">
                <div class="col-xs-12 col-sm-12  <?php echo $class_hidden_simulacao;?>">
                    <div class="placeholder">Data 1° Parcela:</div>
                    <input id="inputDtPArcAcordo" name="dt_primeira_parcela" type="text"
                        placeholder="Data da primeira parcela" class="form-control with-placeholder "
                        <?php echo $readonly_edit_nu_parc_acordo;?> />
                </div>
            </div>
            <div class="row mg-tp-10">
                <div class="col-xs-12 col-sm-12  <?php echo $class_hidden_simulacao;?>">
                    <div class="placeholder">Desconto Total:</div>
                    <input id="inputDescontoTotal" name="desconto_total" type="text" placeholder="Desconto total"
                        value="0" class="form-control with-placeholder " <?php echo $readonly_edit_nu_parc_acordo;?> />
                </div>
            </div>
        </div>
    </form>
</div>


<form id="form_edit_parcela" action="javascript:editar_parcelas()">
    <?php
		$plano = 0;
		$soma_original = 0;
		$soma_correcao = 0;
		$soma_juros = 0;
		$soma_honorarios = 0;	
		$soma_corrigido = 0;
		$soma_para_acordo = false;
		$total_parcelas = count($parcelas);
		$parcela_em_atraso = false;
		$atualiza_2_via = false;
		$pode_zerar = false;
		
		foreach($parcelas as $parcela){ $plano++; ?>

    <div class="row">


        <div class="col-xs-12">
            <h4>Parcela <?php echo $parcela['nu_parcela'];?>
                -
                <?php 


					$parcela_checked = 0;
					$parcela_readonly = 0;
					$class_to_datepicker="dt_parc_contrato";
					$soma_para_acordo = false;
					$readonly_edit_dt_pagamento = " readonly ";
					$readonly_edit_vl_parcela 	 = "  ";
					$parcela_a_vencer = false;
					
					//if ($parcela_em_atraso){				   
					//	$parcela_em_atraso = false;
					//}
					
					if ($atualiza_2_via){				   
						$atualiza_2_via = false;
					}				   
					
					$readonly_edit_dt_vencimento = " readonly ";				   
					if ($status_contrato == "pendente") {
						$readonly_edit_dt_vencimento = "";	
					}
					
					$parcela_id    		   = $parcela['id'];				   
					$vl_parcela    		   = $parcela['vl_parcela'];
					$vl_correcao_monetaria = $parcela['vl_correcao_monetaria'];
					$vl_juros      		   = $parcela['vl_juros'];
					$vl_honorarios 		   = $parcela['vl_honorarios']; 
					$vl_corrigido  		   = $parcela['vl_corrigido']; 
					$liquidada_no_cadastro = $parcela['liquidada_no_cadastro']; 
					$simulada			   = $parcela["simulada"];
					$dt_vencimento		   = $parcela['dt_vencimento'];
					$data_atual			   = new DateTime();
					$dt_vencimento_date_60_dias	   = new DateTime($dt_vencimento);
					$dt_vencimento_date_60_dias	   = $dt_vencimento_date_60_dias->add(new DateInterval('P60D'));
					$dt_pagamento		   = $parcela['dt_pagto'];
					if(!empty($parcela['dt_credito']) && $parcela['dt_credito']!='0000-00-00'){
						$dt_pagamento = $parcela['dt_credito'];
					}
					
					$vl_pagamento		   = $parcela["vl_pagto"];
                    $tratar_ted		       = $parcela["tratar_ted"];
                    $arquivos_id_retorno   = $parcela["arquivos_id_retorno"];
                    $nu_linha_retorno      = $parcela["nu_linha_retorno"];
                    $pessoas_id_atualizacao= $parcela["pessoas_id_atualizacao"];
                    $fl_negativada         = $parcela["fl_negativada"];
                    $pode_negativar        = $parcela["vencida_5_dias"];
                    $motivo_zerada_parc    = $parcela["motivo_zerado"];
                    $observacao_zerado_parc= $parcela["observacao_zerado"];
					$tooltip_obs =0;


                    if ($fl_negativada == "S"){
                        $pode_negativar = true;
                    }
                    
					$pessoa_liquidou       = "";
					if(!empty($parcela["nome"])){
                   	 $pessoa_liquidou       = $parcela["nome"];
					}
					
					
					$data_liquidou         = "";
					if(!empty($parcela["dt_processo_pagto"])){
                   	 $data_liquidou         = ConverteData($parcela["dt_processo_pagto"]);
					}
					
					$virou_indap_bloq_desfaz_pagto = false;
					
					if($liquidada_no_cadastro == "S"){
						echo "<span class='green_light'>Liquidada no cadastro</span>";
						$readonly_edit_dt_vencimento = " readonly ";	
						$class_to_datepicker="";
						$parcela_checked = 0;
						$parcela_readonly = 1;
					}
                    elseif(($status_contrato == "acao_judicial") && ( $vl_pagamento < 0.01   )){
                        echo "<span class='blue_light'>Em ação judicial</span>";
                        $class_to_datepicker="";
                        $parcela_checked = 0;
                        $parcela_readonly = 1;

                        if ($pessoas_id_atualizacao != ""){
							if($pessoa_liquidou != ""){
                                echo ", por: ".$pessoa_liquidou;
                            }
							if($data_liquidou != ""){
                                echo ", em: ".$data_liquidou;
                            }
                        }
                    }
					elseif((($status_contrato == "parcialmente_em_acordo")||($status_contrato == "em_acordo"))&&($simulada == "S") && ( $vl_pagamento < 0.01   )){
                        echo "<span class='blue_light'>Em Acordo</span>";
                        $class_to_datepicker="";
                        $parcela_checked = 0;
                        $parcela_readonly = 1;
                        $soma_para_acordo = true;
                    }
					elseif(!empty($dt_pagamento) && $dt_pagamento != '0000-00-00' && $liquidada_no_cadastro == "N"  ){
						echo "<span id='st_parc_".$parcela_id."' class='green_light '>Liquidada";

                        if (($motivo_zerada_parc != "")&&($vl_pagamento == 0)){
                            echo " (".$motivo_zerada_parc;
                            if ($observacao_zerado_parc != ""){
                                $tooltip_obs = 1;
                            }
                            echo ")";
                        }
                        elseif (($motivo_zerado != "")&&($vl_pagamento == 0)){
						    echo " (".$motivo_zerado.")";
                        }


						if ($arquivos_id_retorno != ""){
                            echo ", arquivo: ".$arquivos_id_retorno.", linha: ".$nu_linha_retorno;
						}
						elseif ($pessoas_id_atualizacao != ""){
							if($pessoa_liquidou != ""){
								echo ", por: ".$pessoa_liquidou; 
							}
							if($data_liquidou != ""){
									echo ", em: ".$data_liquidou;
							}
                        }
                        elseif ($status_contrato == "virou_inadimplente"){
						    echo ", por: Virou inadimplente";
							if($data_liquidou != ""){
								echo " em: ".$data_liquidou;
							}
							$virou_indap_bloq_desfaz_pagto = true;
                        }
                        else{ //if (($data_liquidou != "null")&&($data_liquidou != "0000-00-00 00:00:00")){
                            echo " manualmente ";
							if($data_liquidou != ""){
								echo " em: ".$data_liquidou;
							}
							$virou_indap_bloq_desfaz_pagto = false;
                        }
                        echo "</span>"; 
						
						if( $tooltip_obs ){ ?>
                <i class="fa fa-info-circle fs-17 mg-lf-15 red_light" data-toggle="tooltip" data-placement="left"
                    title="<?php echo  $observacao_zerado_parc ; ?>"></i>
                <?php
						}
						
                        if(consultaPermissao($ck_mksist_permissao,"desfazer_liquidar_parcelas","editar") &&
                            (($motivo_zerado == "null") || trim($motivo_zerado)=='' || empty($motivo_zerado) ) &&
                            ($virou_indap_bloq_desfaz_pagto == false) && ($arquivos_id_retorno == "") &&
                            ($status_contrato != "virou_inadimplente")&&(($status_contrato != "pendente")&&($data_liquidou != "null")&&($data_liquidou != "0000-00-00 00:00:00"))){
                            echo "<span id='desliq_parc_".$parcela_id."' class='red_light fs-12 mg-lf-15 pointer' onclick='desfazer_liquid_parc(".$parcela_id.");'><i class='fa fa-usd'></i> Desfazer pagamento</span>";
                        }

                        $readonly_edit_vl_parcela 	 = " readonly ";
                        $readonly_edit_dt_vencimento = " readonly ";
						$class_to_datepicker="";
						$parcela_checked = 0;
						$parcela_readonly = 1;
						
					}
					elseif(date('Ymd') <= DataInvertida($dt_vencimento)){
						if ((($status_contrato == "parcialmente_em_acordo") || ($status_contrato == "em_acordo"))&&($simulada == "S" )){
							echo "<span class='blue_light'>Em Acordo</span>";
							$readonly_edit_dt_pagamento = " readonly ";
						}
						else{
							echo 'A vencer ';
							$readonly_edit_dt_pagamento = "";
							$readonly_edit_dt_vencimento = "";
							$parcela_a_vencer = true;
                            $pode_zerar = true;
						}
						$parcela_readonly = '';
						$parcela_readonly = 0;
						if(empty($possui_simulada))
							$parcela_checked = 1;
						
					}	
					else{
                        $dias_diferenca = round((strtotime('now') - strtotime($dt_vencimento)) / 86400);

						echo "<span class='red_light'>Atrasada - $dias_diferenca dias</span>";
						$readonly_edit_dt_pagamento = "";
						$parcela_checked = 1;
						$parcela_readonly = 1;
						$parcela_em_atraso = true;
						if(empty($dt_pagamento) || $dt_pagamento == '0000-00-00'){
							$atualiza_2_via = true;
						}
                        $pode_zerar = true;
					}
					
					if (($simulada == "S" )&&($status_contrato != "parcialmente_em_acordo")&&($status_contrato != "em_acordo")&&($status_contrato != "virou_inadimplente")  &&(empty($dt_pagamento) || $dt_pagamento == '0000-00-00')  ){ 

						$possui_simulada=1;
						$soma_para_acordo = true;
						$parcela_checked = 1;
					}
									   
					if ($status_contrato == "virou_inadimplente"){
                        $readonly_edit_vl_parcela 	 = " readonly ";
                        $readonly_edit_dt_pagamento = " readonly ";
					}

									   
					if ($soma_para_acordo){
						$soma_original   += $vl_parcela;
						$soma_correcao   += $vl_correcao_monetaria;
						$soma_juros      += $vl_juros;
						$soma_honorarios += $vl_honorarios;	
						$soma_corrigido  += $vl_corrigido;
					}	
					// echo "<h1>".$cadastrando==false."</h1>";
                    if($_SESSION['perfil_id'] != 1 && $_SESSION['perfil_id'] != 3 && !$cadastrando){ //FINANCEIRO OU ADM
						$readonly_edit_dt_vencimento = " readonly ";
                        if ($status_contrato == 'pendente') $readonly_edit_dt_vencimento = "";
					} 
					else {
						if (!$readonly_edit_dt_pagamento) $readonly_edit_dt_vencimento = "";
					}

				?>
            </h4>
            <div class="row">
                <div class="col-xs-12 col-sm-1 <?php echo $class_hidden." ".$class_hidden_simulacao;?>">
                    <input type="checkbox" id="parcela<?php echo $plano;?>check"
                        name="parcela<?php echo $parcela_id;?>check"
                        class="form-control check_parcelas <?php if ($parcela_em_atraso || $parcela_a_vencer) echo "mark" ?> parcelas hg-20 "
                        <?php if($parcela_checked)echo 'checked="checked"';?>
                        <?php if($parcela_readonly)echo 'readonly';?>
                        onclick="control_check_parcela(<?php echo $parcela_readonly;?> , <?php echo $parcela_checked;?>, <?php echo $plano;?> , <?php echo $total_parcelas;?> );" />
                </div>

                <div class="col-xs-12 col-sm-2 pd-lr-2">
                    <div class="placeholder">Parcela:</div>
                    <input id="inputParc<?php echo $plano;?>valor" name="parcela<?php echo $parcela_id;?>valor"
                        type="text" placeholder="Valor" class="form-control with-placeholder vl_parc_contrato"
                        value="<?php echo $vl_parcela;?>" onBlur="ajusta_valor(<?php echo $plano;?>)"
                        <?php echo $readonly_edit_vl_parcela;?> />
                </div>

                <div class="col-xs-12 col-sm-1  pd-lr-2 <?php echo $class_juros_multa?>">
                    <div class="placeholder">Correção:</div>
                    <input id="inputParc<?php echo $plano;?>corre" name="parcela<?php echo $parcela_id;?>corre"
                        type="text" placeholder="Correção Monetária" class="form-control with-placeholder"
                        value="<?php echo $vl_correcao_monetaria;?>" readonly />
                </div>

                <div class="col-xs-12 col-sm-1 pd-lr-2 <?php echo $class_juros_multa?>">
                    <div class="placeholder">Juros:</div>
                    <input id="inputParc<?php echo $plano;?>juros" name="parcela<?php echo $parcela_id;?>juros"
                        type="text" placeholder="Juros" class="form-control with-placeholder"
                        value="<?php echo $vl_juros;?>" readonly />
                </div>

                <div class="col-xs-12 col-sm-1 pd-lr-2 <?php echo $class_juros_multa?>">
                    <div class="placeholder">Honorários:</div>
                    <input id="inputParc<?php echo $plano;?>honor" name="parcela<?php echo $parcela_id;?>honor"
                        type="text" placeholder="Honorários" class="form-control with-placeholder"
                        value="<?php echo $vl_honorarios;?>" readonly />
                </div>

                <div class="col-xs-12 col-sm-1  pd-lr-2 <?php echo $class_juros_multa?>">
                    <div class="placeholder">Corrigido:</div>
                    <input id="inputParc<?php echo $plano;?>corrigido" name="parcela<?php echo $parcela_id;?>corgd"
                        type="text" placeholder="Corrigido" class="form-control with-placeholder"
                        value="<?php echo $vl_corrigido;?>" readonly />
                </div>

                <div class="col-xs-12 col-sm-1   pd-lr-2 ">
                    <div class="placeholder">Vencimento:</div>
                    <input id="inputParc<?php echo $plano;?>prev" name="parcela<?php echo $parcela_id;?>venci"
                        type="text" placeholder="Vencimento"
                        class="form-control with-placeholder <?php if(trim($readonly_edit_dt_vencimento) != 'readonly') echo $class_to_datepicker;?> pd-lf-5"
                        value="<?php echo ConverteData($dt_vencimento);?>" <?php echo $readonly_edit_dt_vencimento;?> />
                </div>

                <div class="col-xs-12 col-sm-1  pd-lr-2 ">
                    <div class="placeholder">Pagamento:</div>
                    <input id="inputParc<?php echo $plano;?>pagto" name="parcela<?php echo $parcela_id;?>pagto"
                        type="text" placeholder="Pagamento"
                        class="form-control with-placeholder <?php echo $class_to_datepicker;?> pd-lf-5"
                        value="<?php echo ConverteData($dt_pagamento);?>" <?php echo $readonly_edit_dt_pagamento;?>
                        onchange="liquidando_manual(<?php echo $plano;?> );" />
                </div>

                <div class="col-xs-12 col-sm-1   pd-lr-2 ">
                    <div class="placeholder">Vl Pago:</div>
                    <input id="inputParc<?php echo $plano;?>vpago" name="parcela<?php echo $parcela_id;?>vpago"
                        type="text" placeholder="Valor Pago" class="form-control with-placeholder vl_mask"
                        value="<?php echo $vl_pagamento;?>" <?php echo $readonly_edit_dt_pagamento;?>
                        onchange="liquidando_manual(<?php echo $plano;?>);" />
                </div>

                <div class="col-xs-12 col-sm-1   pd-lr-2 ">
                    <input type="checkbox" id="parcela<?php echo $plano;?>tratar_ted"
                        name="parcela<?php echo $parcela_id;?>trted" class="form-control check_parcelas hg-20 "
                        <?php if($tratar_ted)echo 'checked="checked"';?> <?php echo $readonly_edit_dt_pagamento;?> />
                    Não gerar TED
                </div>

                <?php if ($pode_negativar){ ?>
                <div class="col-xs-12 col-sm-1   pd-lr-2 ">
                    <input type="checkbox" id="parcela<?php echo $plano;?>negativa"
                        name="parcela<?php echo $parcela_id;?>negat" class="form-control check_parcelas hg-20 "
                        <?php if($fl_negativada=="S"){echo 'checked="checked"'; }?>
                        <?php echo $readonly_edit_dt_pagamento;?>
                        <?php if (trim($readonly_edit_dt_pagamento)!="readonly"){ ?>
                        onclick="desnegativarParcela('parcela<?php echo $plano;?>negativa', '<?php echo $parcela_id;?>');"
                        <?php } ?> /> Negativar
                </div>
                <?php }?>

                <!-- ZERAR PARCELAS-->
                <?php if(($pode_zerar)&&($motivo_zerada_parc == "")){ ?>
                <div class="col-xs-12 col-sm-1   pd-lr-2 pd-tp-12">
                    <a href="javascript:modal_zerar_parcela_unica(<?php echo $plano.",".$parcela_id?>)">
                        <!--<i class="fa fa-file-o" aria-hidden="true"></i>-->
                        Zerar Parcela
                    </a>
                </div>
                <?php } ?>


                <?php if( ($atualiza_2_via)&&($status_contrato != "pendente" )){ ?>
                <div class="col-xs-12 col-sm-1 ">
                    <!--<div class="pointer" onclick="gera_segunda_via_parcela(
						 <?php //echo $parcela_id;?>,<?php //echo $contratos->id; ?>);">
                            <i class="fa fa-refresh" aria-hidden="true"></i> 2° via
						 </div>-->					
					<?php
					if ($dt_vencimento_date_60_dias < $data_atual) { ?>
						<a href="#" onclick="alimenta_modal_recalcular_boletos(<?php echo $parcela_id ?>)"
							target="_blank">
							<!--<i class="fa fa-file-o" aria-hidden="true"></i>-->
							Recalcular e gerar novo boleto
						</a>
					<?php 
					} else {?>
						<a href="https://unicred-florianopolis.cobexpress.com.br/default/segunda-via" target="_blank"> <i
							class="fa fa-file-o" aria-hidden="true"></i> 2ª via Boleto </a>

					<?php 
					}?>

                </div>
                <?php }
					  elseif(trim($readonly_edit_dt_pagamento) == ""){
                          if ($status_contrato == 'confirmado' && $arquivo_id != '') {
                            $novo_boleto = '';
                          } else {
                            $novo_boleto = $class_gerar_boleto;
                          }
				?>
				<div class="col-xs-12 col-sm-1 <?php echo $novo_boleto?> pd-tp-12">
					<?php
					if ($dt_vencimento_date_60_dias < $data_atual) { ?>
						<a href="<?php echo $link."/inc/boleto/gerar_boleto.php?id=".$contratos->id."&p=".$parcela_id;?>"
							target="_blank">
							<!--<i class="fa fa-file-o" aria-hidden="true"></i>-->
							Recalcular e gerar novo boleto
						</a>
					<?php 
					} else {?>
						<a href="<?php echo $link."/inc/boleto/gerar_boleto.php?id=".$contratos->id."&p=".$parcela_id;?>"
							target="_blank">
							<!--<i class="fa fa-file-o" aria-hidden="true"></i>-->
							Boleto
						</a>

					<?php 
					}?>
                </div>
                <?php }

				?>

            </div>



        </div>
    </div>

    <?php
		}
        $soma_corrigido  = $soma_original+$soma_correcao+$soma_juros+$soma_honorarios;
    ?>

</form>

<div class="<?php echo $class_gerar_boleto?>">

    <a href="<?php echo $link."/inc/boleto/gerar_boleto.php?id=".$contratos->id;?>" class="btn btn-info fl-rg mg-tp-15"
        target="_blank">
        <i class="fa fa-download" aria-hidden="true"></i>Baixar todos os boletos</a>

</div>


<!-- modal zerar parcelas-->
<div class="modal fade" id="md_cadastro_zera_parcelas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="static">
    <div class="modal-dialog wd-90p" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a id="go_to_md_cadastro_zera_parcelas" class="smoothscroll hidden"
                    href="#md_cadastro_zera_parcelas_tt"></a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="md_cadastro_zera_parcelas_tt"></h4>
            </div>
            <div class="modal-body" id="md_cadastro_zera_parcelas_bd">
                <div class="panel ">
                    <div class="">
                        Editar Contrato
                        <div class="panel-body pan">
                            <form id="form_zera_parcelas">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-brown " onClick="javascript:zerar_parcelas()">Zerar
                        Parcelas</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$recoverInstrucao =$info_contrato[0]['instrucao'];
if(empty($recoverInstrucao)){ 
	$recoverInstrucao = "Após vencimento, mora dia de R$ 0,54<br>Após vencimento, multa de 2%<br>Título sujeito a negativação e protesto 03 dias após o vencimento.";
} ?>
<input type="hidden" id="recoverInstrucao" value="<?php echo $recoverInstrucao;?>" />

<script>
<?php
    echo "$('#btInstrucoes').hide();";

    if ($parcela_em_atraso){
        echo "$('#btVirarAcaoJudicial').show();";
    }
    else{
        echo "$('#btVirarAcaoJudicial').hide();";
    }

	if ($status_contrato == "confirmado" ){
		echo "$('#btSalvarParcelas').show();";
        echo "$('#btZerarParcelas').show();";
        echo "$('#btConfirmarContrato').hide();";
		echo "$('#btConfirmarAcordo').hide();";
		echo "$('#btVirarInadimplente').hide();";
		if ((($tipo_contrato == "adimplencia")) && ($parcela_em_atraso)){
			echo "$('#btVirarInadimplente').show();";
		}
		if ($contrato_liquidado){
            echo "$('#btSalvarParcelas').hide();";
            echo "$('#btZerarParcelas').hide();";
        }
		echo "$('#btSalvarParcelas').text('Salvar parcelas');";
	}
	elseif (($status_contrato == "pendente" )||($status_contrato == "em_acordo") ||($status_contrato == "parcialmente_em_acordo")||($status_contrato == "virou_inadimplente")||($status_contrato == "acao_judicial") ){
		echo "$('#btConfirmarContrato').show();";
		echo "$('#btConfirmarAcordo').show();";
		echo "$('#btSalvarParcelas').show();";
		echo "$('#btVirarInadimplente').hide();";
        echo "$('#btZerarParcelas').show();";
		
		echo "$('#btSalvarParcelas').text('Salvar parcelas e continuar depois');";
		echo "$('#soma_original').html('<strong>Dívida original:</strong> R$ ".Format($soma_original, "numero")."');";
		echo "$('#soma_correcao').html('<strong>Valor correção:</strong> R$ ".Format($soma_correcao, "numero")."');";
		echo "$('#soma_juros').html('<strong>Valor juros:</strong> R$ ".Format($soma_juros, "numero")."');";
		echo "$('#soma_honorarios').html('<strong>Valor honorários:</strong> R$ ".Format($soma_honorarios, "numero")."');";
        echo "$('#soma_corrigido').html('<strong>Dívida atual:</strong> R$ ".Format($soma_corrigido, "numero")."');";
        if (!isnull($dt_correcao_monetaria))
		    echo "$('#data_at_mon').html('<strong>Data ult. atualização monetária:</strong> ".date('d/m/Y',strtotime($dt_correcao_monetaria))."');";
		echo "$('#inputVlAcordo').val('".$soma_corrigido."');";		
		
		if (($status_contrato == "em_acordo")||($status_contrato == "acao_judicial")){
			echo "$('#btSalvarParcelas').hide();";
			echo "$('#btConfirmarContrato').hide();";
			echo "$('#btConfirmarAcordo').hide();";
		}
		elseif ($status_contrato == "parcialmente_em_acordo"){
			echo "$('#btSalvarParcelas').text('Salvar parcelas');"; 
			echo "$('#btConfirmarContrato').hide();";
			echo "$('#btConfirmarAcordo').hide();";
            echo "$('#btZerarParcelas').show();";
		}
		elseif ($status_contrato == "virou_inadimplente"){
			echo "$('#btSalvarParcelas').hide();"; 
			echo "$('#btConfirmarContrato').hide();";
		}
		elseif(($status_contrato == "pendente")&&($gerar_boleto == "S")){
            echo "$('#btInstrucoes').show();";
        }
		if ($tipo_contrato == "adimplencia"){
			echo "$('#btConfirmarAcordo').hide();";			
		}
		elseif ($tipo_contrato == "inadimplencia"){
			echo "$('#btConfirmarContrato').hide();";			
		}
	}
?>
var total = <?php echo $total;?>;
var plano = <?php echo $plano;?>;

function ajusta_valor() {

    soma = 0;
    divisao = 0;
    for (i = 1; i <= plano; i++) {
        plus = $('#inputParc' + i + 'valor').val();
        soma = parseFloat(soma) + parseFloat(plus);
    }
    if (parseFloat(soma).toFixed(2) != parseFloat(total).toFixed(2)) {
        jAlert('Valor das parcelas: R$ ' + number_format(soma, 2) + ' é diferente do valor total: R$ ' + number_format(
            total, 2) + ' do contrato', 'Oops');
        return false;
    }
    return true;
}

function atualiza_simulacao(id_contrato) {
    acao = "atualiza_simulacao";
    contratos = $('#form_atualiza_simulacao').serializeArray();
    parcelas_a_atualizar = $('#form_edit_parcela').serializeArray();
    $.post("<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=";?>" + acao, {
        contratos: contratos,
        parcelas_a_atualizar: parcelas_a_atualizar
    }, function(result) {
        var result = jQuery.parseJSON(result);
        if (result.status == 1) {

            //jAlert(result.msg,'Valores atualizados!','ok');

            get_parcelas_pos_simulacao(id_contrato);

        } else {
            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
        }
    });

}

function get_parcelas_pos_simulacao(contrato_id) {
    //busca parcelas deste pedido
    $.get("<?php echo $link."/adm/contratos/form_edit_parcelas.php";?>", {
        acao: 'get_parcelas',
        contrato_id: contrato_id
    }, function(result) {
        if (result == '0') {
            alert('nenhuma parcela a recuperar');
        } else {
            grava_parcela = $('#inputQtParcelasAcordo').val();
            grava_data = $('#inputDtPArcAcordo').val();

            $('#md_edit_parcelas_bd_body_form').html(result);

            $('.vl_parc_contrato').maskMoney();
            $('.dt_parc_contrato').mask("99/99/9999");
            $('.dt_parc_contrato').datepicker({
                dateFormat: 'dd/mm/yy'
            });


            $('#inputDtPArcAcordo').mask("99/99/9999");
            $('#inputDtPArcAcordo').datepicker({
                dateFormat: 'dd/mm/yy'
            });
            $('#inputDescontoTotal').maskMoney();

            $('#inputQtParcelasAcordo').val(grava_parcela);
            $('#inputDtPArcAcordo').val(grava_data);

            $('#md_edit_parcelas').modal('show');
        }
    });

}

function confirmar_contrato() {
    $('button').attr('disabled','disabled');

    id_contrato = $("input#inputIdSimulacao").val();
    status_contrato = $("input#inputStatusContrato").val();

    $.getJSON("<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=verifica_informacoes";?>", {
        id_contrato: id_contrato
    }, function(result) {
        if (result.status == 1) {

            jAlert(result.msg,
                'Não foi possível confirmar o contrato!     Você deve atualizar os cadastros.    Salve as parcelas para continuar depois!',
                'ok');

        } else {

            if (ajusta_valor() == true) {
                jConfirm(
                    'Tem certeza que deseja confirmar este contrato?<br>Após esta confirmação, as informações não poderão ser mais editadas!',
                    'Confirmar Contrato?',
                    function(r) {
                        if (r) {
                            editar_parcelas();
                            setTimeout(function() {
                                $.getJSON(
                                    "<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=confirmar_contrato";?>", {
                                        id_contrato: id_contrato,
                                        status_contrato: status_contrato
                                    },
                                    function(result) {
                                        if (result.status > 0) {


                                            filtrar_fields();
                                            $('#md_edit_parcelas').modal('hide');
                                            if (result.status == '9') {
                                                jAlert(result.msg,
                                                    'Não foi possível salvar as alterações!',
                                                    'alert');
                                            } else {
                                                jAlert(result.msg, 'Bom trabalho!', 'ok');
                                            }

                                        } else {
                                            jAlert(result.msg,
                                                'Não foi possível salvar as alterações!',
                                                'alert');
                                        }
                                    });
                            }, 4000);


                        }
                    });
            }
        }
        $('button').removeAttr('disabled');
    });
}

function confirmar_acordo() {
    $('button').attr('disabled','disabled');
    if (ajusta_valor() == true) {
        $('#buttonSimulacao').click();
        id_contrato = $("input#inputIdAcordo").val();
        vl_acordo = $("input#inputVlAcordo").val();
        nu_parcelas = $("input#inputQtParcelasAcordo").val();
        dt_primeira_parcela = $("input#inputDtPArcAcordo").val();
        desconto_acordo = $("input#inputDescontoTotal").val();


        if (!$.isNumeric(id_contrato)) {
            jAlert('Problema ao confirmar acordo.', 'Não foi possível salvar as alterações!', 'alert');
            $('button').removeAttr('disabled');
        } else if (!$.isNumeric(nu_parcelas) || nu_parcelas < 1) {
            jAlert('Preencha o número de parcelas do acordo.', 'Oops!', 'alert');
            $('button').removeAttr('disabled');
        } else if (dt_primeira_parcela.length != 10) {
            jAlert('Por favor, preencha a data da primeira parcela do acordo!', 'Oops!', 'alert');
            $('button').removeAttr('disabled');
        } else if (!$.isNumeric(desconto_acordo) || desconto_acordo < 0) {
            jAlert('Preencha o valor de desconto do acordo.', 'Oops!', 'alert');
            $('button').removeAttr('disabled');
        } else {

            jConfirm(
                'Tem certeza que deseja confirmar este acordo?<br>Após esta confirmação, as informações não poderão ser mais editadas!',
                'Confirmar Acordo?',
                function(r) {
                    if (r) {
                        editar_parcelas();
                        $.getJSON(
                            "<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=gerar_acordo";?>", {
                                id_contrato: id_contrato,
                                qt_parcelas_acordo: nu_parcelas,
                                vl_acordo: vl_acordo,
                                dt_primeira_parcela: dt_primeira_parcela,
                                desconto_acordo: desconto_acordo
                            },
                            function(result) {
                                if (result.status == 1) {
                                    $('#md_edit_parcelas').modal('hide');
                                    jAlert(result.msg, 'Bom trabalho!', 'ok');
                                    filtrar_fields();

                                } else {
                                    jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                                }
                                $('button').removeAttr('disabled');
                            });
                    } else {
                        $('button').removeAttr('disabled');
                    }
                });
        }
    }
}


function control_check_parcela(ready_only, checked, plano, total_parcelas) {
    if (ready_only == 1) {
        if (checked == 1) {
            mantem_check = true;
        } else {
            mantem_check = false;
        }
        $("#parcela" + plano + "check").prop('checked', mantem_check);
        $('.parcelas.check_parcelas.mark').prop('checked', mantem_check);
        return 0;
    }


    if ($("#parcela" + plano + "check").prop('checked') == false) {
        //se desmarcou uma parcela - desmarca todas as seguintes
        for (i = total_parcelas; i > plano; i--) {
            if ($("#parcela" + i + "check").prop('checked') == true && !$("#parcela" + i + "check").is('[readonly]')) {
                $("#parcela" + i + "check").prop('checked', false);
            }
        }
    } else {
        //confirma se todas as anteriores estão selecionadas
        for (i = 0; i < plano; i++) {
            if ($("#parcela" + i + "check").prop('checked') == false && !$("#parcela" + i + "check").is('[readonly]')) {
                $("#parcela" + i + "check").prop('checked', true);
            }
        }
    }

}

function virar_inadimplente() {
    $('button').attr('disabled','disabled');
    id_contrato = $("input#inputIdAcordo").val();

    jConfirm(
        'Tem certeza que deseja tranformar este acordo em inadimplente?<br>Após esta confirmação, as informações não poderão ser mais editadas!',
        'Tranformar acordo em inadimplente?',
        function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=copy_contrato_adimp_para_inadimplente";?>", {
                        id_contrato: id_contrato
                    },
                    function(result) {
                        if (result.status == 1) {
                            $('#md_edit_parcelas').modal('hide');
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                            filtrar_fields();
                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                        }
                        $('button').removeAttr('disabled');
                    });
            } else {
                jAlert('As informações estão seguras.', 'Ação Cancelada!', 'ok');
                $('button').removeAttr('disabled');
            }
        });
}

function liquidando_manual(plano) {

    valor_vazio = data_vazia = 0;

    valor = $('#inputParc' + plano + 'vpago').val();
    if (valor == "" || valor == 0 || valor == '0.00' || valor == '0,00') {
        campo = 'inputParc' + plano + 'vpago';
        valor_vazio = 1;
    }

    data = $('#inputParc' + plano + 'pagto').val();
    if (data == "" || data == '00/00/0000') {
        campo = 'inputParc' + plano + 'pagto';
        data_vazia = 1;
    }

    if ((valor_vazio == 1 && data_vazia == 0) || (valor_vazio == 0 && data_vazia == 1)) {
        //alert('valor: |'+valor+'|   e data: |'+data+'| MSG');

        msg =
            " Para liquidar uma parcela manualmente você deve preencher o valor pago e a data de pagamento, caso contrário se assumirá pagamento na data da atualização e no valor atual da parcela.";

        //ativa msg 

        html_msg =
            "<div class='msg_liquidando'> <i class='fa fa-times pointer pull-right' onclick='javascript:close_msg_liquidando();'></i>" +
            msg + "</div>";
        $(".msg_liquidando").remove();
        $('#' + campo).after(html_msg);
        $('#' + campo).focus();
    } else {
        //remove msg
        $(".msg_liquidando").remove();
        //alert('valor: |'+valor+'|   e data: |'+data+'| preenchidos');
    }


}

function close_msg_liquidando() {
    $(".msg_liquidando").remove();
}

function zerar_parcelas() {

    var id_contrato = $("input#inputIdSimulacao").val();
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
                            $('#md_cadastro_zera_parcelas').modal('hide');
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

function modal_zerar_parcelas() {
    $('#md_cadastro_zera_parcelas_tt').html('Zerar parcelas do contrato');

    $('html, body, #md_cadastro_zera_parcelas , #md_edit_parcelas').animate({
        scrollTop: 0
    }, 'fast');
    $('#md_cadastro_zera_parcelas').modal('show');
}

function modal_zerar_parcela_unica(numero_parcela, id_parcela) {
    $('#md_cadastro_zera_parcelas_tt').html('Zerar parcela ' + numero_parcela + ' do contrato');
    $("input#inputIdParcela").val(id_parcela);
    $('html, body, #md_cadastro_zera_parcelas , #md_edit_parcelas').animate({
        scrollTop: 0
    }, 'fast');
    $('#md_cadastro_zera_parcelas').modal('show');
}

function virar_acao_judicial() {
    $('button').attr('disabled','disabled');
    id_contrato = $("input#inputIdAcordo").val();

    jConfirm(
        'Tem certeza que deseja tranformar este contrato em ação judicial?<br>Após esta confirmação, as informações não poderão ser mais editadas!',
        'Tranformar contrato em ação judicial?',
        function(r) {
            if (r) {
                $.getJSON(
                    "<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=virar_acao_judicial";?>", {
                        id_contrato: id_contrato
                    },
                    function(result) {
                        if (result.status == 1) {
                            $('#md_edit_parcelas').modal('hide');
                            jAlert(result.msg, 'Bom trabalho!', 'ok');
                            filtrar_fields();
                        } else {
                            jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                        }
                        $('button').removeAttr('disabled');
                    });
            } else {
                jAlert('As informações estão seguras.', 'Ação Cancelada!', 'ok');
            }
        });
}

function desnegativarParcela(id, parcela_id) {

    if ($('#' + id).is(':checked')) {
        msg = 'Tem certeza que deseja negativar esta parcela?';
        antes_do_clique = false;
        negativa = 'S';
    } else {
        msg = 'Tem certeza que deseja desnegativar esta parcela?';
        antes_do_clique = true;
        negativa = 'N';
    }

    jConfirm(msg + '<br>!', 'Confirmar ação?', function(r) {
        if (r) {
            $.getJSON(
                "<?php echo $link."/repositories/contratos/contratos.ctrl.php?acao=desnegativar_parcela";?>", {
                    parcela_id: parcela_id,
                    negativa: negativa
                },
                function(result) {
                    if (result.status == 1) {
                        jAlert(result.msg, `Bom trabalho!`, 'ok');
                        window.open('https://spcitajai.cdl-sc.org.br/spc-web/login/SC005');
                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as alterações!', 'alert');
                    }
                });
        } else {
            $('#' + id).attr('checked', antes_do_clique);
        }
    });
}
</script>