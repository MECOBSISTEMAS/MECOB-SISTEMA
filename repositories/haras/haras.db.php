<?php
class harasDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_haras($haras, &$conexao_BD_1, $filtros, $order,$inicial =0,$limit=30){
		$where 	 = " where 1 = 1 ";
		
		$select =" select h.*, p.id as proprietario_id, p.nome as prop_nome, p.email as proprietario_email, 
				   p.foto as proprietario_foto, p.cpf_cnpj as proprietario_cpf_cnpj
				   from haras h
				   left join pessoas p on p.id = h.proprietario_id 
		";
		
		$where .= $this->retorna_where_lista_haras($filtros, $haras);
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  h.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		#echo $select.$where.$orderby.$limite;
		return $conexao_BD_1->query($select.$where.$orderby.$limite);	
	}
	
	function lista_totais_haras($filtros, &$conexao_BD_1){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(h.id) total_haras
					from haras h ";
					
		$where .= $this->retorna_where_lista_haras($filtros);
		#echo $select.$where;	
		$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["total_haras"];
	}
	
	function retorna_where_lista_haras($filtros, $haras=''){
					
		$where = " ";
		
		if($haras!=''){
			if($haras->id !=""){ $where .= " AND h.id = ".$haras->id ; }	
		}
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_haras"])){
				$busca = trata_busca_sql_score($filtros["filtro_haras"]);
				if(isset($busca['multi'])){
					$where .= "  AND MATCH (h.nome,h.telefone,h.contato)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";	
				}
				else{
					$where .="  AND  (    remove_acentos(h.nome) LIKE '%".$busca['simples']."%' 
										  OR remove_acentos(h.contato) LIKE '%".$busca['simples']."%' 
										  OR h.telefone LIKE '%".$busca['simples']."%' 
										   )  ";		
				}
			}
			
			if(!empty($filtros["filtro_proprietario"])){
				$busca = trata_busca_sql_score($filtros["filtro_proprietario"]);
				if(isset($busca['multi'])){
					$where .= "  AND 
									(
										MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  
										OR
										
										MATCH (h.proprietario_nome,h.proprietario_doc)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  
									)
										
							 ";			
				}
				else{			   
					$where .=" AND (
										  (     remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
										  		OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
										  		OR p.email LIKE '%".$busca['simples']."%' 
										  		OR p.cpf_cnpj LIKE '%".$busca['simples']."%'
										   ) 
										   OR
										   (
										   		remove_acentos(h.proprietario_nome) LIKE '%".$busca['simples']."%' 
												OR h.proprietario_doc LIKE '%".$busca['simples']."%' 
										   )
								   )
							 ";	
				}
										   
			}
		}
		
		
		return $where;
		
	}
	

	function remover_haras($haras_id, &$conexao_BD_1){
		$delete = "delete from haras  where id =  ".$haras_id;
        $retorno = $conexao_BD_1->query_atualizacao($delete);
		return $retorno;
	}
	
	function remover_proprietario($haras_id, &$conexao_BD_1){
		$update = " update haras set 
					proprietario_nome=null, proprietario_doc=null, proprietario_id = null
					where 	id = ".$haras_id."   ";
		return $conexao_BD_1->query_atualizacao($update);
	}
	
	function lista_haras_ajax($palavra, $conexao_BD_1){
		
		$select = " select h.* ";
		$from = " from haras h  ";	
		$where = " WHERE 1=1 ";
		
		$busca = trata_busca_sql_score($palavra);
		if(isset($busca['multi'])){
			$select .= " ,MATCH (h.nome,h.telefone,h.contato)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) AS  SCORE ";
			$where .= " AND MATCH (h.nome,h.telefone,h.contato)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)   ";
			$order = " ORDER BY SCORE DESC, h.nome  LIMIT 15 ";	
		}
		else{
			$where .="  AND  (  remove_acentos(h.nome) LIKE '%".$busca['simples']."%' 
								OR
								remove_acentos(h.telefone) LIKE '%".$busca['simples']."%' 
								OR
								remove_acentos(h.contato) LIKE '%".$busca['simples']."%' 
						     )  ";
			$order = "  ORDER BY	h.nome 	   LIMIT 15 ";	
		}	
							
		#echo $select.$where.$order;
		return $conexao_BD_1->query($select.$from.$where.$order);		
	
	}
	
	
			
}

?>