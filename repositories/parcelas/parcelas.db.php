<?php
class parcelasDB{
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
	}
	
	public function __get($atrib){
		return $this->$atrib;
	}	

	// case
	// 	when (tp_contrato_boleto = 'adimplencia') and contratos_id_pai is null and  vl_pagto is not null and vl_pagto > 0 then 
	// 		((ct.honor_adimp / 100) *  vl_pagto  ) 
	// 	when (tp_contrato_boleto = 'adimplencia') and contratos_id_pai is null then 
	// 		((ct.honor_adimp / 100) *  vl_corrigido  )  
	// 	when vl_pagto is not null and vl_pagto > 0 then 
	// 		vl_pagto-( vl_pagto / (1+ (ct.honor_adimp / 100)  ))
	// 	else 
	// 		vl_corrigido-( vl_corrigido / (1+ (ct.honor_adimp / 100)  ))
	// 	end as vl_honorarios
	
	function lista_parcelas( &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30,$echo=0){
		
		
		$select =" select 
		p.*, ct.id as ct_id , pv.nome , pc.nome as comprador_nome, 
		ct.descricao as ct_descricao,  ct.status as ct_status, ct.repasse as repasse,
		ct.nu_parcelas as ct_nu_parcelas,
		ev.nome as ev_nome,
		
		case
		when vl_corrigido is  null or vl_corrigido <= vl_parcela
		then vl_parcela 
		else vl_corrigido
		end as corrigido, 

		case
		when (tp_contrato_boleto = 'adimplencia') then 
			((ct.honor_adimp / 100) *  vl_pagto) 
		else 
			(vl_pagto - ((vl_pagto * 100) / (100 + ct.honor_inadimp)))
		end vl_honorarios
		
		from contrato_parcelas p ";
		$join = "  join contratos ct on ct.id = p.contratos_id 
		
		left join pessoas pc on pc.id = ct.comprador_id
		left join pessoas pv on pv.id = ct.vendedor_id
		left join eventos ev on ev.id = ct.eventos_id
		";
		
		$where  = " where (ct.suspenso = 'N' or ct.suspenso is null) ";
		$where .= $this->retorna_where_lista_parcelas( $filtros); 	
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  p.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		if( $echo){
			echo $select.$join.$where.$orderby.$limite;
		}
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcela Query ' . json_encode($select.$join.$where.$orderby.$limite));
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcela Query ' . json_encode($filtros));
		return $conexao_BD_1->query($select.$join.$where.$orderby.$limite);	
	}
	
	function lista_totais_parcelas($filtros, &$conexao_BD_1){
		
		$select = " select 
		count(p.id) total_parcelas,
		sum( case
		when vl_corrigido is not null and vl_corrigido > vl_parcela
		then vl_corrigido
		else p.vl_parcela 
		end ) as vl_parcela ,
		
		sum(case
		when (tp_contrato_boleto = 'adimplencia') then 
			((ct.honor_adimp / 100) *  if(vl_pagto > 0, vl_pagto, vl_corrigido))  
		else 

			(if(vl_pagto > 0, vl_pagto, vl_corrigido) - ((if(vl_pagto > 0, vl_pagto, vl_corrigido) * 100) / (100 + ct.honor_inadimp)))			

			end) vl_honorarios,
		
		sum(vl_pagto) as vl_pagto
		
		from contrato_parcelas p					
		";
		
		$join = "  join contratos ct on ct.id = p.contratos_id 
		left join pessoas pc on pc.id = ct.comprador_id
		left join pessoas pv on pv.id = ct.vendedor_id
		";
		
		$where  = " where   1=1   ";
		$where .= $this->retorna_where_lista_parcelas($filtros); 
		

		//syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcela Query ' . json_encode($select.$join.$where));
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcela Query ' . json_encode($filtros));

		// echo $select.$join.$where;				
		$retorno['totais'] =  $conexao_BD_1->query($select.$join.$where);	
		// echo $select.$join.$where;
		// exit;

		return $retorno;
		
		
	}
	
	function retorna_where_lista_parcelas($filtros){

		// var_dump(json_encode($filtros));
		// exit;
		
		$where = " and (dt_pagto is null || dt_pagto = '0000-00-00' || (vl_pagto is not null and vl_pagto>0 )) ";
		
		if($filtros!=""){ 
			if(!empty($filtros["filtro_contrato_id"])){
				$where .= "  AND ct.id =".$filtros["filtro_contrato_id"]." ";	
			}
			if(!empty($filtros["filtro_ted_id"])){
				$where .= "  AND p.teds_id =".$filtros["filtro_ted_id"]." ";	
			}
			if(!empty($filtros["filtro_tpcontrato"])){ 
				if ($filtros["filtro_tpcontrato"] == 'repasse') {
					$where .= "  AND ct.repasse = 'S' ";	
				} else {
					$where .= "  AND (ct.repasse = 'N' OR ct.repasse is null) AND (ct.suspenso = 'N' OR ct.suspenso is null) AND ct.tp_contrato_boleto = '".$filtros["filtro_tpcontrato"]."' ";	
				}
			} else {
				$where .= "  AND  (ct.suspenso = 'N' OR ct.suspenso is null)";
			}
			if(!empty($filtros["filtro_status_ct"])){ 
				$filtro_status_ct = explode(',',$filtros["filtro_status_ct"]);

				$where .= "  AND (";
				foreach ($filtro_status_ct as $key => $value) {
					$where .= "  ct.status = '$value' OR";	
				}
				$where = substr($where,0,strlen($where)-3);
				$where .= ")";				
			} else {
				// Filtra excluidos
				if (empty($_SESSION['perfil_id']) ) {  
					$where .=  " AND NOT ct.status = 'excluido' ";
				}
			}
			
			if(!empty($filtros["filtro_status"])){
				if($filtros["filtro_status"]==1){
					$where .= "  AND (p.dt_vencimento < '".date('Y-m-d')."') 
					and ( p.dt_pagto is null or p.dt_pagto= '0000-00-00' )";	
				}
				elseif($filtros["filtro_status"]==2){
					$where .= "  AND (p.dt_vencimento >= '".date('Y-m-d')."') 
					and ( p.dt_pagto is null or p.dt_pagto= '0000-00-00' )";	
				}
				elseif($filtros["filtro_status"]==3){
					$where .= "  and ( p.dt_pagto is not null and p.dt_pagto <> '0000-00-00' )";
				}
				elseif($filtros["filtro_status"]==4){
					$where .= "  and p.teds_id is not null ";
				}
				elseif($filtros["filtro_status"]==5){
					$where .= "  and p.fl_negativada = 'S' ";
				}
				elseif($filtros["filtro_status"]==6){
					$dias_vencidos = 5;
					$data = new DateTime();
					$data->sub(new DateInterval("P".$dias_vencidos."D"));
					$data = $data->format('Y-m-d');
					$where .= " and p.dt_vencimento <= '$data' and p.dt_vencimento > '2019-03-15' and p.fl_negativada = 'N' and (p.dt_pagto is null or p.dt_pagto = '0000-00-00') and (ct.suspenso = 'N' or ct.suspenso is null) ";
				}
				elseif($filtros["filtro_status"]==7){
					$where .= " and p.fl_negativada = 'S' and (p.dt_pagto is not null and p.dt_pagto <> '0000-00-00') ";
				}
			}
			
			if(!empty($filtros["filtro_per_ini"]) || !empty($filtros["filtro_per_fim"])){
				if(!empty($filtros["filtro_per_ini"]) && !empty($filtros["filtro_per_fim"])){
					$where .= " AND 
					case
					when dt_credito is not null and dt_credito <> '0000-00-00'
					then dt_credito
					when dt_pagto is not null and dt_pagto <> '0000-00-00'
					then dt_pagto
					else dt_vencimento
					end
					BETWEEN '".ConverteData($filtros["filtro_per_ini"])."' and '".ConverteData($filtros["filtro_per_fim"])."' ";
				}
				elseif(!empty($filtros["filtro_per_ini"])){
					$where .= " AND case
					when dt_credito is not null and dt_credito <> '0000-00-00'
					then dt_credito
					when dt_pagto is not null and dt_pagto <> '0000-00-00'
					then dt_pagto
					else dt_vencimento
					end = '".ConverteData($filtros["filtro_per_ini"])."' ";
				}
				elseif(!empty($filtros["filtro_per_fim"])){
					$where .= " AND case
					when dt_credito is not null and dt_credito <> '0000-00-00'
					then dt_credito
					when dt_pagto is not null and dt_pagto <> '0000-00-00'
					then dt_pagto
					else dt_vencimento
					end= '".ConverteData($filtros["filtro_per_fim"])."' ";
				}
			}
			
			
			if(!empty($filtros["filtro_vendedor"])){
				
				if($filtros["filtro_vendedor"][0] == '*'){
					$filtro_vendedor = str_replace('*','',$filtros["filtro_vendedor"]);
					$busca_array = explode(' ',$filtro_vendedor);
					foreach($busca_array as $busca_item){
						if(!empty(trim($busca_item)))
						$where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca_item."%' 
						OR remove_acentos(pv.apelido) LIKE '%".$busca_item."%' 
						OR pv.email LIKE '%".$busca_item."%' 
						OR pv.cpf_cnpj LIKE '%".$busca_item."%'
						)  ";	
					}
				}
				else{
					if ($_SESSION['perfil_id'] == NULL){
						$where .= " AND ct.vendedor_id = ".$filtros["filtro_vendedor"];
					} else {
					
						$busca = trata_busca_sql_score($filtros["filtro_vendedor"]);
						if(isset($busca['multi'])){
							$where .= "  AND MATCH (pv.nome,pv.apelido,pv.email,pv.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";			
						}
						else{
							$where .="  AND  (       remove_acentos(pv.nome) LIKE '%".$busca['simples']."%' 
							OR remove_acentos(pv.apelido) LIKE '%".$busca['simples']."%' 
							OR pv.email LIKE '%".$busca['simples']."%' 
							OR pv.cpf_cnpj LIKE '%".$busca['simples']."%'
							)  ";	
						}
					}
				}
				
			}
			
			if(!empty($filtros["filtro_comprador"])){
				if($filtros["filtro_comprador"][0] == '*'){
					$filtro_comprador = str_replace('*','',$filtros["filtro_comprador"]);
					$busca_array = explode(' ',$filtro_comprador);
					foreach($busca_array as $busca_item){
						if(!empty(trim($busca_item)))
						$where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca_item."%' 
						OR remove_acentos(pc.apelido) LIKE '%".$busca_item."%' 
						OR pc.email LIKE '%".$busca_item."%' 
						OR pc.cpf_cnpj LIKE '%".$busca_item."%'
						)  ";	
					}
				}
				else{
					if ($_SESSION['perfil_id'] == NULL){
						$where .= " AND ct.comprador_id = ".$filtros["filtro_comprador"];
					} else {
						$busca = trata_busca_sql_score($filtros["filtro_comprador"]);
						if(isset($busca['multi'])){
							$where .= "  AND MATCH (pc.nome,pc.apelido,pc.email,pc.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";			
						}
						else{
							$where .="  AND  (       remove_acentos(pc.nome) LIKE '%".$busca['simples']."%' 
							OR remove_acentos(pc.apelido) LIKE '%".$busca['simples']."%' 
							OR pc.email LIKE '%".$busca['simples']."%' 
							OR pc.cpf_cnpj LIKE '%".$busca['simples']."%'
							)  ";	
						}
					}
				}
				
			}

			if(!empty($filtros["filtro_dia"])){
				$dia_filtro = $filtros["filtro_dia"];
				$where .= " AND DAY(dt_vencimento) = '$dia_filtro' ";
			}
			
			if(!empty($filtros["filtro_descricao"])){
				
				if($filtros["filtro_descricao"][0] == '*'){
					$filtro_descricao = str_replace('*','',$filtros["filtro_descricao"]);
					$busca_array = explode(' ',$filtro_descricao);
					foreach($busca_array as $busca_item){
						if(!empty(trim($busca_item)))
						$where .="  AND ( remove_acentos(ct.descricao) LIKE '%".$busca_item."%' )  ";	
					}
				}
				else{
					$where .="  AND  ( remove_acentos(ct.descricao) LIKE '%".$filtros["filtro_descricao"]."%' )  ";	
				}
				
			}
			
		}
		return $where;	
	}
	
	function carteira_cliente( &$conexao_BD_1,  $id){
		$select =" 		
		SELECT  
		SUM(receber) receber,
		SUM(transferir) transferir, 
		SUM(pagar) pagar
		FROM (  
			SELECT  
			SUM(vl_corrigido) AS receber,
			0 AS transferir, 
			0 AS pagar 
			
			FROM contrato_parcelas cp
			join contratos c on c.id = cp.contratos_id
			WHERE 
			c.vendedor_id = ".$id." and
			c.status in ('confirmado' , 'acao_judicial' ) and
			(dt_pagto is null or dt_pagto = '0000-00-00') and 
			(vl_pagto is null or vl_pagto = 0 ) and 
			dt_vencimento >= '".date('Y-m-d')."' 
			
			union
			
			
			SELECT  
			0 AS receber,
			SUM(vl_pagto) AS transferir, 
			0 AS pagar 
			
			FROM contrato_parcelas cp
			join contratos c on c.id = cp.contratos_id
			WHERE 
			c.vendedor_id = ".$id." and
			c.status in ('confirmado' , 'acao_judicial' ) and
			(dt_pagto is not null and dt_pagto <> '0000-00-00') and 
			(vl_pagto is not null and vl_pagto > 0 ) and 
			teds_id is null 
			
			union
			
			
			SELECT  
			0 AS receber, 
			0 AS transferir, 
			SUM(vl_corrigido) AS pagar 
			
			FROM contrato_parcelas cp
			join contratos c on c.id = cp.contratos_id
			WHERE 
			c.comprador_id = ".$id." and
			c.status in ('confirmado' , 'acao_judicial' ) and
			(dt_pagto is null or dt_pagto = '0000-00-00') and (vl_pagto is null or vl_pagto = 0 )
			
			union
			
			
			SELECT  
			0 AS receber, 
			0 AS transferir, 
			SUM(vl_corrigido) AS pagar 
			
			FROM contrato_parcelas cp
			join boletos_avulso c on c.id = cp.boletos_avulso_id
			WHERE 
			c.pessoas_id = ".$id." and 
			(dt_credito is null or dt_credito = '0000-00-00') and 
			(vl_pagto is null or vl_pagto = 0 )  
			
			
			) fluxo 
			
			";
			
			//echo $select;				
			return $conexao_BD_1->query($select);	
		}
		
		function repasse_cliente( &$conexao_BD_1,  $id){
			
		}
		
		function fluxo_cliente( &$conexao_BD_1,  $id){
			
			$data_inicio = date("Y-m-").'01';
			// $data_prox = new DateTime($data_inicio);
			// $data_prox->add(new DateInterval("P3M"));
			// $mes_final = $data_prox->format('Y-m-');
			
			// $var_date = explode( '-', $mes_final );
			// $lt_day_month = cal_days_in_month( CAL_GREGORIAN, $var_date[ 1 ], $var_date[ 0 ] );
			// $data_final = $mes_final.$lt_day_month;
			
			
			$select =" 		
			SELECT 
			mes, 
			SUM(receber) receber, 
			SUM(pagar) pagar
			FROM (  
				SELECT 
				DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
				SUM(vl_corrigido) AS receber, 
				0 AS pagar ,
				0 AS pagar_avulso
				
				FROM contrato_parcelas cp
				join contratos c on c.id = cp.contratos_id
				WHERE 
				c.vendedor_id = ".$id." and
				c.status in ('confirmado' , 'acao_judicial' ) and
				(dt_pagto is null or dt_pagto = '0000-00-00') and 
				(vl_pagto is null or vl_pagto = 0 ) and 
				dt_vencimento >= '".$data_inicio."' and
				(c.suspenso = 'N' or c.suspenso is null)
				group by DATE_FORMAT(dt_vencimento, '%Y-%m')
				
				union
				
				SELECT 
				DATE_FORMAT(dt_credito, '%Y-%m') AS mes,
				SUM(vl_pagto) AS receber, 
				0 AS pagar ,
				0 AS pagar_avulso
				
				FROM contrato_parcelas cp
				join contratos c on c.id = cp.contratos_id
				WHERE 
				c.vendedor_id = ".$id." and
				
				teds_id is null and
				( dt_pagto is not null and dt_pagto <> '0000-00-00' and (vl_pagto is not null and vl_pagto > 0 ) ) and 
				dt_credito >= '".$data_inicio."' and
				(c.suspenso = 'N' or c.suspenso is null)
				group by DATE_FORMAT(dt_credito, '%Y-%m')
				
				union
				
				SELECT 
				DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
				0 AS receber, 
				SUM(vl_corrigido) AS pagar ,
				0 AS pagar_avulso
				
				FROM contrato_parcelas cp
				join contratos c on c.id = cp.contratos_id
				WHERE 
				c.comprador_id = ".$id." and
				c.status in ('confirmado' , 'acao_judicial' ) and
				(dt_pagto is null or dt_pagto = '0000-00-00') and 
				(vl_pagto is null or vl_pagto = 0 )  and 
				dt_vencimento >= '".$data_inicio."' and
				(c.suspenso = 'N' or c.suspenso is null)
				group by DATE_FORMAT(dt_vencimento, '%Y-%m')
				
				UNION 
				
				SELECT 
				DATE_FORMAT(dt_vencimento, '%Y-%m') AS mes,
				0 AS receber, 
				SUM(vl_corrigido) AS pagar,
				0 AS pagar_avulso
				
				FROM contrato_parcelas cp
				join boletos_avulso c on c.id = cp.boletos_avulso_id
				WHERE 
				c.pessoas_id = ".$id." and 
				(dt_credito is null or dt_credito = '0000-00-00') and 
				(vl_pagto is null or vl_pagto = 0 )  and 
				dt_vencimento >= '".$data_inicio."'
				group by DATE_FORMAT(dt_vencimento, '%Y-%m')
				
				) fluxo GROUP BY mes
				
				";
				
				//echo $select;				
				return $conexao_BD_1->query($select);	
				

			}		

			function parcelas_colocar_spc( &$conexao_BD_1, $dias_vencidos ){
				$data = new DateTime();
				$data->sub(new DateInterval("P".$dias_vencidos."D"));
				$data = $data->format('Y-m-d');
				$select = "SELECT c.id, (
					SELECT p.id FROM ocorrencias o inner join pessoas p on p.id = o.pessoas_id where contratos_id = c.id and perfil_id is not null order by o.id desc limit 1
					) as pessoas_id_ocorrencia,
					(SELECT GROUP_CONCAT(p.id SEPARATOR ',') from pessoas p where p.supervisor = 'S') as supervisores,
					c.pessoas_id_inclusao from contrato_parcelas cp inner join contratos c on c.id = cp.contratos_id where c.status = 'confirmado' and dt_vencimento <= '$data' and dt_vencimento > '2019-03-20' and fl_negativada = 'N' and (dt_pagto is null or dt_pagto = '0000-00-00') and (c.suspenso = 'N' or c.suspenso is null) and (c.tp_contrato <> 'repasse') group by c.id order by id";
				// echo $select;
				return $conexao_BD_1->query($select);	
			}

			function parcelas_retirar_spc( &$conexao_BD_1, $dias_vencidos ){
				// $data = new DateTime();
				// $data->sub(new DateInterval("P".$dias_vencidos."D"));
				// $data = $data->format('Y-m-d');
				$select = "SELECT c.id, (
					SELECT p.id FROM ocorrencias o inner join pessoas p on p.id = o.pessoas_id where contratos_id = c.id and perfil_id is not null order by o.id desc limit 1
					) as pessoas_id_ocorrencia,
					(SELECT GROUP_CONCAT(p.id SEPARATOR ',') from pessoas p where p.supervisor = 'S') as supervisores,
					c.pessoas_id_inclusao from contrato_parcelas cp inner join contratos c on c.id = cp.contratos_id where fl_negativada = 'S' and (dt_pagto is not null and dt_pagto <> '0000-00-00') group by c.id order by id";
				// echo $select;
				return $conexao_BD_1->query($select);	
			}			

			function promessas_pagamento_nao_cumpridas_ontem(&$conexao_BD_1){
				$select = "SELECT o.contratos_id,
					o.pessoas_id,
					(select count(cp.id) from contrato_parcelas cp where cp.contratos_id = o.contratos_id and cp.dt_pagto >= DATE(o.data_ocorrencia)) as parcelas_pagas
					from ocorrencias o 
					join contratos c on c.id = o.contratos_id
					where
					o.promessa_pagamento = DATE_SUB(curdate(),INTERVAL 1 DAY)";
				return $conexao_BD_1->query($select);
			}
		}
		
