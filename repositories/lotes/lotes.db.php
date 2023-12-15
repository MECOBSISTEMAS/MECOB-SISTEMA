<?php
class lotesDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_lotes($lotes, $conexao_BD_1,  $filtros, $order, $inicial,$limit=30){
		$where 	 = " where 1 = 1 ";
		
		$select =" select l.* from lotes l ";
		
		$where .= $this->retorna_where_lista_lotes($filtros, $lotes); 	
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  l.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		#echo $select.$where.$order;
		return $conexao_BD_1->query($select.$where.$orderby.$limite);	
	}
	
	function lista_totais_lotes($filtros, &$conexao_BD_1){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(l.id) total_lotes 	from lotes l ";
					
		$where .= $this->retorna_where_lista_lotes($filtros); 
									
		$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["total_lotes"];
	}
	
	function retorna_where_lista_lotes($filtros, $lotes=''){
					
		$where = " ";
		
		if($lotes!=''){
			if($lotes->id !=""){ $where .= " AND l.id = ".$lotes->id ; }
		}
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_tipo"])){
				$where .= "  AND l.tipo ='".$filtros["filtro_tipo"]."'  ";	
			}

			if(!empty($filtros["filtro_lotes"])){
				$busca = trata_busca_sql_score($filtros["filtro_lotes"]);
				if(isset($busca['multi'])){
					$where .= "  AND MATCH (l.nome, l.num_registro)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";	
				}
				else{
					$where .="  AND  (    remove_acentos(l.nome) LIKE '%".$busca['simples']."%' 
										  OR l.num_registro LIKE '%".$busca['simples']."%' 
										   )  ";	
				}
										   
			}
		}
		
		return $where;
		
	}
	

	function remover_lotes($lotes_id, &$conexao_BD_1){
		$delete = "delete from lotes  where id =  ".$lotes_id;
        $retorno = $conexao_BD_1->query_atualizacao($delete);
		return $retorno;
	}
	
	function lista_lotes_ajax($palavra, $conexao_BD_1){
		
		$select = " select l.* ";
		$from = " from lotes l  ";	
		$where = " WHERE 1=1 ";
		
		$busca = trata_busca_sql_score($palavra);
		if(isset($busca['multi'])){
			$select .= " ,MATCH (nome,num_registro)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) AS  SCORE ";
			$where .= " AND MATCH (l.nome, l.num_registro)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)   ";
			$order = " ORDER BY SCORE DESC, l.nome  LIMIT 15 ";	
		}
		else{
			$where .="  AND  (    remove_acentos(l.nome) LIKE '%".$busca['simples']."%' 
							     OR l.num_registro LIKE '%".$busca['simples']."%' 
							 )  ";
			$order = "  ORDER BY	l.nome 	   LIMIT 15 ";	
		}	
							
		#echo $select.$where.$order;
		return $conexao_BD_1->query($select.$from.$where.$order);		
	
	}
	
	
			
}

?>