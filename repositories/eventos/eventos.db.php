<?php
class eventosDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_eventos($eventos, &$conexao_BD_1, $filtros, $order, $inicial,$limit=30){
		$where 	 = " where 1 = 1 ";
		
		$select =" select e.*, p.id as leiloeiro_id, p.nome as leiloeiro_nome, p.email as leiloeiro_email, 
				   p.foto as leiloeiro_foto, p.cpf_cnpj as leiloeiro_cpf_cnpj
				   from eventos e
				   left join pessoas p on p.id = e.leiloeiro_id
		";
		
		$where .= $this->retorna_where_lista_eventos($filtros, $eventos); 
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  e.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		
		$order = " order by dt_evento desc";
		#echo $select.$where.$orderby.$limite;
		return $conexao_BD_1->query($select.$where.$orderby.$limite);	
	}
	
	function lista_totais_eventos($filtros, &$conexao_BD_1){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(e.id) total_eventos
					from eventos e
					
					";
		$where .= $this->retorna_where_lista_eventos($filtros); 
					
		$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["total_eventos"];
	}
	
	function retorna_where_lista_eventos($filtros, $eventos=''){
					
		$where = " ";
		
		if($eventos!=''){
			if($eventos->id !=""){ $where .= " AND e.id = ".$eventos->id ; }
		}
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_tipo"])){
				$where .= "  AND e.tipo ='".$filtros["filtro_tipo"]."'  ";	
			}
			if(!empty($filtros["filtro_data"])){
				$where .= "  AND e.dt_evento ='".ConverteData($filtros["filtro_data"])."'  ";	
			}
			if(!empty($filtros["filtro_eventos"])){
				if($filtros["filtro_eventos"][0] == '*'){
						$filtro_evento = str_replace('*','',$filtros["filtro_eventos"]);
						$busca_array = explode(' ',$filtro_evento);
						foreach($busca_array as $busca_item){
							if(!empty(trim($busca_item))) 
								$where .="  AND  (  remove_acentos(e.nome) LIKE '%".$busca_item."%' )  ";
						}
				}
				else{
					$busca = trata_busca_sql_score($filtros["filtro_eventos"]);
					
					if(isset($busca['multi'])){
						$where .= "  AND MATCH (e.nome)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";	
					}
					else{
						$where .="  AND  (  remove_acentos(e.nome) LIKE '%".$busca['simples']."%' )  ";	
					}
				}
			}
		}
		
		return $where;
		
	}
	

	function remover_eventos($evento_id, &$conexao_BD_1){
		$delete = "delete from eventos  where id =  ".$evento_id;
        $retorno = $conexao_BD_1->query_atualizacao($delete);
		return $retorno;
	}
	
	function lista_eventos_ajax($palavra, $conexao_BD_1){
		
		$select = " select e.*, p.id as leiloeiro_id, p.nome as leiloeiro_nome, p.email as leiloeiro_email, 
				  	p.foto as leiloeiro_foto, p.cpf_cnpj as leiloeiro_cpf_cnpj
				  	";
		$from = " from eventos e
				  left join pessoas p on p.id = e.leiloeiro_id ";	
		$where = " WHERE 1=1 ";
		
		$busca = trata_busca_sql_score($palavra);
		if(isset($busca['multi'])){
			$select .= " ,MATCH (e.nome)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) AS  SCORE 
						 ,MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) AS  PSCORE  ";
			
			$where .= " AND (MATCH (e.nome)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)
						OR MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE))
					  ";
			
			$order = " ORDER BY (SCORE+PSCORE) DESC, e.nome  LIMIT 15 ";	
		}
		else{
			$where .="  AND  (   remove_acentos(e.nome) LIKE '%".$busca['simples']."%' 
							     OR remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
								 OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
								 OR remove_acentos(p.email) LIKE '%".$busca['simples']."%' 
								 OR remove_acentos(p.cpf_cnpj) LIKE '%".$busca['simples']."%' 
							 )  ";
			$order = "  ORDER BY	e.nome 	   LIMIT 15 ";	
		}	
							
		#echo $select.$from.$where.$order;
		return $conexao_BD_1->query($select.$from.$where.$order);		
	
	}
	
	
			
}

?>