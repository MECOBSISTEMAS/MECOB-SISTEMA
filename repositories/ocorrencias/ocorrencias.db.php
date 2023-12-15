<?php
class ocorrenciasDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_ocorrencias( &$conexao_BD_1, $comprador_id =0, $vendedor_id=0, $limit = ''){
		
		
		$select =" select 
				   o.* , o.id as o_id, o.status as o_status ,
				   c.* , c.id as c_id, c.status as c_status ,
				   user.nome
				   from ocorrencias o 
				   join contratos c on c.id = o.contratos_id
				   join pessoas user on user.id = o.pessoas_id
				   ";
		
		$where 	 = " where 1 = 1 ";
		
		if(is_numeric($comprador_id) && $comprador_id>0){
			$where 	 .= " and c.comprador_id =  ".$comprador_id;
		}
		if(is_numeric($vendedor_id) && $vendedor_id>0){
			$where 	 .= " and c.vendedor_id =  ".$vendedor_id;
		}	
		
		$orderby = " ORDER BY o.data_ocorrencia desc ";
		
		if(is_numeric($limit))
			$orderby  .= ' limit '.$limit;
		
		#echo $select.$where.$order;
		return $conexao_BD_1->query($select.$where.$orderby);	
	}
	
	function lista_ocorrencias_id( &$conexao_BD_1, $contrato_id=0, $comprador_id=0, $vendedor_id=0, $limit = ''){
		
		
		$select =" select 
					o.* , o.id as o_id, o.status as o_status ,
					c.* , c.id as c_id, c.status as c_status ,
					user.nome
					from ocorrencias o 
					join contratos c on c.id = o.contratos_id
					join pessoas user on user.id = o.pessoas_id
				";
		
		$where 	 = " where 1 = 1 ";
		
		$where 	 .= " and o.contratos_id =  ".$contrato_id;
		
		$orderby = " ORDER BY o.data_ocorrencia desc ";
		
		if(is_numeric($limit))
			$orderby  .= ' limit '.$limit;
		
		#echo $select.$where.$order;
		return $conexao_BD_1->query($select.$where.$orderby);	
	}
	

	
	function comprador_do_contrato( $contrato_id , &$conexao_BD_1){
		$select = " select p.* from contratos c
					join pessoas p on p.id = c.comprador_id
					where c.id = ".$contrato_id;
		$retorno =  $conexao_BD_1->query($select);	
		return $retorno[0];
	}
	
	function vendedor_do_contrato( $contrato_id , &$conexao_BD_1){
		$select = " select p.* from contratos c
					join pessoas p on p.id = c.vendedor_id
					where c.id = ".$contrato_id;
		$retorno =  $conexao_BD_1->query($select);	
		return $retorno[0];	
	}
	
	function dividas_comprador(&$conexao_BD_1, $contrato_id , $comprador_id){
		$select = " select c.* , 
					(select sum(case  when vl_corrigido is not null and vl_corrigido> vl_parcela 
											then vl_corrigido 
											else vl_parcela 
										end ) as total from )
					from contratos c
					where c.comprador_id = ".$comprador_id;
					
					
		$select_vencido = " select c.id, c.descricao,c.status,  sum(case 
											when vl_corrigido is not null and vl_corrigido> vl_parcela 
											then vl_corrigido 
											else vl_parcela 
										end ) as total
							from contrato_parcelas cp
							join contratos c on c.id = cp.contratos_id
							where   ( cp.dt_vencimento < '".date('Y-m-d')."'   ) 
									and ( cp.dt_pagto is  null  or cp.dt_pagto = '0000-00-00' )
									and c.comprador_id = ".$comprador_id."
							
							group by c.id, c.descricao, c.status
							order by c.id asc
							";
							
							
		$retorno =  $conexao_BD_1->query($select_vencido);	
		return $retorno;
	}
	
	function select_last_ocor_contrato($contrato_id , &$conexao_BD_1){
		if(!is_numeric($contrato_id)) return '';
		$select = " select * from ocorrencias where contratos_id =  ".$contrato_id."  order by id desc limit 1 ";
		$retorno =  $conexao_BD_1->query($select);	
		return $retorno;
	}

	function insere_ocorrencia_cliente(&$conexao_BD_1, $contratos_id, $msg){
		try {
			$cliente = $_SESSION['id'];
			$insert = "INSERT INTO ocorrencias (`status`,mensagem,pessoas_id,contratos_id,data_ocorrencia) VALUES
			(
				'Cliente',
				'$msg',
				$cliente,
				$contratos_id,
				now()
			)";
			$conexao_BD_1->query_inserir($insert);
			return true;
		} catch (\Throwable $th) {
			return false;
		}
	}
	
	
	
			
}

?>