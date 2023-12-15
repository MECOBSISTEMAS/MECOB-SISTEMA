<?php
class alertasDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_alertas($alertas, $conexao_BD_1,  $filtros, $carregados=''){
		$where 	 = " where (visualizado = 'N' or visualizado is null)";
		
		$select =" select a.* from alertas a ";
		
		$where .= $this->retorna_where_lista_alertas($filtros, $alertas); 	
		
		$orderby = " ORDER BY a.data_alerta DESC";
		
		$limit="";
		if(is_numeric($carregados)){
				$limit = " LIMIT ".$carregados.",  10";
		}
		
		
		//echo $select.$where.$orderby.$limit;
		return $conexao_BD_1->query($select.$where.$orderby.$limit);	
	}

	function lista_alertas_usuario_ativo($alertas, $conexao_BD_1,  $filtros, $carregados=''){
		$where 	 = " where 	pessoas_id_destino = ".$_SESSION['id'];
		
		$select =" SELECT a.*,p.nome as nome_remetente, p.eh_admin as remetente_admin, p.eh_user as remetente_user from alertas a join pessoas p on p.id = a.pessoas_id_cadastro";
		
		$where .= $this->retorna_where_lista_alertas_usuario_ativo($filtros, $alertas); 	
		
		$orderby = " ORDER BY a.data_alerta DESC";
		
		$limit="";
		if(is_numeric($carregados)){
				$limit = " LIMIT ".$carregados.",  10";
		}
		
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou DB ' . json_encode($select.$where.$orderby.$limit));
		// echo $select.$where.$orderby.$limit;
		// exit;
		return $conexao_BD_1->query($select.$where.$orderby.$limit);	
	}
	
	function lista_total_alertas($alertas,  $conexao_BD_1,$filtros){
		
		$select =" select count(*) total from alertas a ";
		
		$where 	 = " where visualizado = 'N' ";
		$where .= $this->retorna_where_lista_alertas($filtros, $alertas); 	
		
		#echo $select.$where.$order;
		$retorno = $conexao_BD_1->query($select.$where);	
		return $retorno[0]['total'];
	}

	function lista_total_alertas_atrasados($alertas,  $conexao_BD_1,$filtros){
		
		$select =" select count(*) total from alertas a ";
		
		$where 	 = " where (concluido is null or concluido = 'N') and (dt_prazo < now() and dt_prazo is not null and dt_prazo <> '0000-00-00') ";
		$where .= $this->retorna_where_lista_alertas($filtros, $alertas); 	
		
		#echo $select.$where.$order;
		$retorno = $conexao_BD_1->query($select.$where);	
		return $retorno[0]['total'];
	}	
	
	function retorna_where_lista_alertas($filtros, $alertas=''){
					
		$where = " ";
		
		if($alertas!=''){
			if($alertas->id !=""){ $where .= " AND a.id = ".$alertas->id ; }
			if($alertas->pessoas_id_destino !=""){ $where .= " AND a.pessoas_id_destino = ".$alertas->pessoas_id_destino ; }
		}
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_criador"])){
				$where .= "  AND a.pessoa_id_cadastro  =".$filtros["filtro_criador"]."  ";	
			}
			if(!empty($filtros["filtro_destino"])){
				$where .= "  AND a.pessoa_id_destino  = ".$filtros["filtro_destino"]."  ";	
			}
		}
		return $where;
		
	}

	function retorna_where_lista_alertas_usuario_ativo($filtros, $alertas=''){
					
		$where = " ";
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_status"])){
				switch ($filtros["filtro_status"]) {
					case 1:
						$where .= "AND a.dt_concluido is not null and a.dt_concluido <> '0000-00-00'";
						//
						break;
					case 2:
						//
						break;
					case 3:
						$where .= "AND a.dt_concluido is not null and a.dt_concluido <> '0000-00-00'";
						break;
					case 4:
						$where .= "AND a.dt_prazo < CURDATE() and (a.dt_concluido is null or a.dt_concluido = '0000-00-00')";
						break;
					
				}
			}
			if(!empty($filtros["filtro_per_ini"])){
				$where .= "AND a.data_alerta >= '".ConverteData($filtros['filtro_per_ini'])."'";
			}
			if(!empty($filtros["filtro_per_fim"])){
				$where .= "AND a.data_alerta <= '".ConverteData($filtros['filtro_per_fim'])." 23:59:59'";
			}
		}
		return $where;
		
	}
	
	function visualizado($pessoas_id,  &$conexao_BD_1){
		$update = "update alertas set visualizado = 'S' where pessoas_id_destino = $pessoas_id" ;
		echo $update;
		$conexao_BD_1->query_atualizacao($update);
	}
	
	function concluido($alerta_id,  &$conexao_BD_1){
		$update = "Update alertas set concluido = 'S', dt_concluido = '".date('Y-m-d H:i:s')."' where id = ".$alerta_id ;
		return $conexao_BD_1->query_atualizacao($update);
	}
	
	
			
}

?>