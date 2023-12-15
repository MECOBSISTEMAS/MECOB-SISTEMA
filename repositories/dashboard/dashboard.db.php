<?php
class dashboard{
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }
	
	function total_boletos_vencidos(&$conexao_BD_1,$tipo, $invertido,$somente_ontem=false){
		// $count = ($count) ? 'count(*)' : '*'

		if (!$somente_ontem){
			$somente_ontem = "(dt_pagto is null or dt_pagto = '0000-00-00')";
			$invertido = ($invertido) ? " and pc.dt_vencimento >= date(now())" : " and pc.dt_vencimento < date(now())";

		} else {
			$somente_ontem = "pc.dt_vencimento = DATE_SUB(date(now()),INTERVAL 1 DAY)";
			$invertido = ($invertido) ? "" : " and (dt_pagto is null or dt_pagto = '0000-00-00')";
		}
		$select = "SELECT 
			count(*) qtd, sum(pc.vl_corrigido) as valor
		from 
			contratos c 
			join 
			contrato_parcelas pc on pc.contratos_id = c.id
		where
			$somente_ontem
			
			$invertido
			and
			c.tp_contrato_boleto = '$tipo'
			and
			(c.status = 'confirmado' or c.status = 'em_acordo' or c.status = 'parcialmente_em_acordo')
			and
			(c.repasse = 'N' or c.repasse is null)
			and
			(c.suspenso = 'N' or c.suspenso is null)
		";

		return $conexao_BD_1->query($select)[0];

	}	
	
	function boletos_de_hoje(&$conexao_BD_1, $liquidados=0, $id_pessoa = "" ){
		$select = " select count(cp.id) qt_parcelas 
						from contrato_parcelas cp
						join contratos c on c.id = cp.contratos_id
					";
					
		$where  = " where ( case
								when dt_pagto is not null and dt_pagto <> '0000-00-00'
								then dt_pagto
								else dt_vencimento
								end  = '".date('Y-m-d')."' ) ";
		//$where .= " and (dt_pagto is null || dt_pagto = '0000-00-00' || (vl_pagto is not null and vl_pagto>0 )) ";
		
		if(!empty($liquidados)){
			$where  .= " and ( cp.dt_pagto is not null  && cp.dt_pagto <> '0000-00-00' ) ";
			$where  .= " AND  (vl_pagto IS NOT NULL AND vl_pagto>0 ) ";
		}
		else{
			$where  .= " AND ( (dt_pagto IS NULL || dt_pagto = '0000-00-00') || (vl_pagto IS NOT NULL AND vl_pagto>0 ))  ";
		}
		
		if ($id_pessoa != ""){
			$where .= " and c.comprador_id = ".$id_pessoa."";
		}
				
		//echo $select.$where;
	 	$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["qt_parcelas"];
	}
	
	function qtd_contratos(&$conexao_BD_1,$inadimplentes="", $id_pessoa){

		if($inadimplentes!=""){		
			$inadimplentes  = "AND ( cp.dt_vencimento  < date(now()) ) 
				and ( cp.dt_pagto is  null  or cp.dt_pagto = '0000-00-00' ) 
				and (c.repasse = 'N' or c.repasse is null)
				and (c.suspenso = 'N' or c.suspenso is null)
				
			";
		}

		if ($id_pessoa != ""){
			$id_pessoa = "AND c.comprador_id = ".$id_pessoa;
		}

		if ($id_pessoa != '' && $inadimplentes != '') $where = 'where '; else $where = '';

		$select = "SELECT count(distinct(c.id)) qtd
			from contrato_parcelas cp
			join contratos c on c.id = cp.contratos_id and c.status = 'confirmado' 
		$where 
			$inadimplentes
			$id_pessoa
		";
		
				
		//echo $select;
	 	$conexao_BD_1->query($select);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["qtd"];
	}
	
	function tt_arquivos(&$conexao_BD_1,$stt_envio =""){
		$select = " select count(*) as tt_arq from arquivos a 
					where tp_arq = 'REMESSA' 
					";
		if($stt_envio == 'enviar'){
			$select .= " and (  dt_envio_banco is null or 
								dt_envio_banco = '' or 
								dt_envio_banco = '0000-00-00' or
								pessoas_id_envio is null or
								pessoas_id_envio = 0	
							)
			";
		}
		$conexao_BD_1->query($select);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["tt_arq"];
	}
	
	function parcelas_vencidas(&$conexao_BD_1, $id_pessoa = "", $tp_contrato=""){
		
		
		$data_atual = date("Y-m-d");
		$data_prox = new DateTime($data_atual);
		$data_prox->sub(new DateInterval("P1D"));
		$data_final = $data_prox->format('Y-m-d'); 
		
		$select = " select c.*, cp.*, c.id as c_id
						from contrato_parcelas cp
						join contratos c on c.id = cp.contratos_id
					";
					
		$where  = " where   ( cp.dt_vencimento  = '".$data_final."' ) and
						 	 ( cp.dt_pagto is  null  or cp.dt_pagto = '0000-00-00' ) ";
		
		
		if ($id_pessoa != ""){
			$where .= " and c.comprador_id = ".$id_pessoa."";
		}
		
		if($tp_contrato != ""){
			$where .= " and c.tp_contrato_boleto = '".$tp_contrato."'  ";
		}
		
		//$where .= " and c.status not like '%pendente%' ";

		$where .= " and (c.suspenso = 'N' or c.suspenso is null) ";
		
		$order = " order by dt_vencimento asc , c.id asc ";
		//echo $select.$where.$order;
	 	return $conexao_BD_1->query($select.$where.$order);	
		
	}
	
	
	function parcelas_aberto(&$conexao_BD_1, $id_pessoa = ""){
		$select = " select c.*, cp.*, c.id as c_id, cp.id as p_id
						from contrato_parcelas cp
						join contratos c on c.id = cp.contratos_id
					";
					
		$where  = " where   ( cp.dt_pagto is  null  or cp.dt_pagto = '0000-00-00' ) ";
		
		
		if ($id_pessoa != ""){
			$where .= " and c.comprador_id = ".$id_pessoa."";
		}
		
		$where .= " and c.status not like '%pendente%' ";
		
		$order = " order by dt_vencimento asc , c.id asc ";
		//echo $select.$where.$order;
	 	return $conexao_BD_1->query($select.$where.$order);	
		
	}
	
	
	function fluxo_este_mes(&$conexao_BD_1, $id_pessoa){
		$array_retorno = array();
		
		$where_pessoa ="";
		if ($id_pessoa != ""){
			$where_pessoa = " and c.comprador_id = ".$id_pessoa."";
		}
		$data_inicio =date("Y-m")."-01";
		$data_ini = new DateTime(date("Y-m")."-01");
		$qt_dias_mes = $data_ini->format('t');
		$data_fim  = new DateTime(date("Y-m")."-".$qt_dias_mes);
		$data_final = $data_fim->format('Y-m-d');
		
		
		$select_recebido = " SELECT 
			sum(vl_pagto) total
		from 
			contrato_parcelas cp 
			join 
			contratos c on c.id = cp.contratos_id
		where
		cp.dt_vencimento >= '$data_inicio' and cp.dt_vencimento < date_add('$data_inicio', interval 1 month)
		and
		(c.suspenso = 'N' or c.suspenso is null)
		and
		(c.repasse = 'N' or c.repasse is null)
		and c.tp_contrato = 'adimplencia'
		and c.status = 'confirmado' 
		and cp.dt_pagto is not null $where_pessoa";
		$conexao_BD_1->query($select_recebido);	
		$reg = $conexao_BD_1->leRegistro();
		$array_retorno['recebido'] =  $reg["total"];
							
		$select_aberto = " SELECT 
			sum(vl_corrigido) total
		from 
			contrato_parcelas cp 
			join 
			contratos c on c.id = cp.contratos_id
		where
		cp.dt_vencimento >= date(now()) and cp.dt_vencimento < date_add('$data_inicio', interval 1 month)
		and
		(c.suspenso = 'N' or c.suspenso is null)
		and
		(c.repasse = 'N' or c.repasse is null)
		and c.tp_contrato = 'adimplencia'
		and c.status = 'confirmado'
		and cp.dt_pagto is null $where_pessoa";
		$conexao_BD_1->query($select_aberto);	
		$reg = $conexao_BD_1->leRegistro();
		$array_retorno['aberto'] =  $reg["total"];
							
		$select_vencido = " SELECT 
			sum(vl_corrigido) total
		from 
			contrato_parcelas cp 
			join 
			contratos c on c.id = cp.contratos_id
		where
		cp.dt_vencimento >= '$data_inicio' and cp.dt_vencimento < date(now())
		and
		(c.suspenso = 'N' or c.suspenso is null)
		and
		(c.repasse = 'N' or c.repasse is null)
		and c.tp_contrato = 'adimplencia'
		and c.status = 'confirmado'
		and cp.dt_pagto is null $where_pessoa";
		$conexao_BD_1->query($select_vencido);	
		$reg = $conexao_BD_1->leRegistro();
		$array_retorno['vencido'] =  $reg["total"];
		
		//echo $select.$where.$order;
	 	return $array_retorno;
	}
	
	function contratos_by_status(&$conexao_BD_1, $status = ''){
		$select = " SELECT  COUNT(c.id) total , sum(c.vl_contrato) valor FROM contratos c  ";
		
		if($status != ''){
			$select  .= " WHERE STATUS = '".$status."' 	";
		}
		
		return $conexao_BD_1->query($select);	
	}
	
	
	
}

?>