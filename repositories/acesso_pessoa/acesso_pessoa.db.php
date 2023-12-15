<?php
class acesso_pessoaDB{
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_acesso_pessoa($acesso_pessoa, &$conexao_BD_1,  $filtros, $order, $inicial,$limit=30){
		
	    $select = " select ap.*, p.nome , p.cpf_cnpj , p.email, s.descricao status_descricao
					from acesso_pessoa ap  	
					join pessoas p on p.id = ap.pessoas_id	
					left join status s ON p.status_id = s.id 			
					";
					
		$where = " WHERE 1=1 ";
		$where .= $this->retorna_where_lista_acessos($filtros, $acesso_pessoa); 
		
				$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  ap.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		#echo $select.$where;
		return $conexao_BD_1->query($select.$where.$orderby.$limite);	
	}
	
	function lista_totais_acessos($filtros, &$conexao_BD_1){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(ap.id) total_acessos	from acesso_pessoa ap ";
					
		$where .= $this->retorna_where_lista_acessos($filtros); 
									
		$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["total_acessos"];
	}
	
	function retorna_where_lista_acessos($filtros, $acesso_pessoa=''){
					
		$where = " ";
		
		// if($acesso_pessoa!=''){
			// if($acesso_pessoa->id !=""){ $where .= " AND ap.id = ".$acesso_pessoa->id ; }
		// }
				
		if($filtros!=""){ 
			
			if(!empty($filtros["filtro_nome"])){
				// $busca = trata_busca_sql_score($filtros["filtro_nome"]);
				
				// if(isset($busca['multi'])){
				// 	$where .= "  AND MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";	
				// }
				// else{
				// 	$where .="  AND  (       remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
				// 						  OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
				// 						  OR p.email LIKE '%".$busca['simples']."%' 
				// 						  OR p.cpf_cnpj LIKE '%".$busca['simples']."%'
				// 						   )  ";	
				// }
				$busca = explode(',',$filtros['filtro_nome']);
				foreach ($busca as $key => $value) {
					// echo $value;
					// exit;
					$where .= " AND (ap.url like '%$value%' or ap.request like '%$value%')";
				}
			}
			if(!empty($filtros["filtro_data"])){
				$where .= " AND ap.data between '".ConverteData($filtros["filtro_data"])." 00:00:00'  and  '".ConverteData($filtros["filtro_data"])." 23:59:59' "; 
			}

		}
		
		return $where;
		
	}
}

?>