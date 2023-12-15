<?php
class pessoasDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_pessoas($pessoas, $inicial,  $limit, $filtros, $order, &$conexao_BD_1){
					
		$select = " select p.*,  s.descricao status_descricao,
					pf.id as perfil_id, pf.descricao as perfil_descricao, pf.dt_atualizacao as perfil_dt_atualizacao, 
					(select count(*) from haras h where h.proprietario_id = p.id  ) as total_haras
					";
		$from = " from pessoas p  ";
		$join = " left join status s ON p.status_id = s.id  ";
		$join .= " left join perfil pf ON pf.id = p.perfil_id  ";
					
		$where = " WHERE 1=1 ";
		
		$where .= $this->retorna_where_lista_pessoas($filtros, $pessoas);
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= " p.nome, p.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		#echo $select.$from.$join.$where.$orderby.$limite;
		return $conexao_BD_1->query($select.$from.$join.$where.$orderby.$limite);	
	}
	
	function lista_totais_pessoas(&$conexao_BD_1, $filtros = "", $pessoas = ""){
					
		$select = " select count(distinct(p.id)) qt_pessoas ";
		$from = " from pessoas p  ";
		$join = " left join status s ON p.status_id = s.id  ";
		$join .= " left join perfil pf ON pf.id = p.perfil_id  ";
					
		$where = " WHERE 1=1 ";
		
		$where .= $this->retorna_where_lista_pessoas($filtros, $pessoas);
		#echo $select.$from.$join.$where;
		return $conexao_BD_1->query($select.$from.$join.$where);	
	}
	
	function retorna_where_lista_pessoas($filtros, $pessoas){
					
		$where = " ";
		
		if($pessoas->id !=""){ $where .= " AND p.id = ".$pessoas->id ; }
		if($pessoas->email !=""){ $where .= " AND p.email = '".$pessoas->email."'" ; }
		if($pessoas->cpf_cnpj !=""){ $where .= " AND p.cpf_cnpj = '".$pessoas->cpf_cnpj."'" ; }
		if($pessoas->eh_user !=""){ $where .= " AND p.eh_user = '".$pessoas->eh_user."'" ; }
		#if($pessoas->eh_admin !=""){ $where .= " AND p.eh_admin = '".$pessoas->eh_admin."'" ; }
		if($pessoas->eh_comprador !=""){ $where .= " AND p.eh_comprador = '".$pessoas->eh_comprador."'" ; }
		if($pessoas->eh_leiloeiro !=""){ $where .= " AND p.eh_leiloeiro = '".$pessoas->eh_leiloeiro."'" ; }
		if($pessoas->eh_vendedor !=""){ $where .= " AND p.eh_vendedor = '".$pessoas->eh_vendedor."'" ; }
				
		if($filtros!=""){ 
			if(!empty($filtros["id_dif"]) ){
				$where .= " AND p.id <> '".$filtros["id_dif"]."' ";
			}
			
			if(!empty($filtros["filtro_status"]) ){
				$where .= " AND s.id = '".$filtros["filtro_status"]."' ";
			}
			
			if(!empty($filtros["filtro_perfil"]) ){
				
				if($filtros["filtro_perfil"]!=2){
					$where .= " AND pf.id = '".$filtros["filtro_perfil"]."' ";
				}
				else{
					$where .= " AND ( p.perfil_id = null or pf.id = '".$filtros["filtro_perfil"]."'  )";
				}
			}
			
			
			if(!empty($filtros["filtro_pessoa"])){
				if($filtros["filtro_pessoa"][0] == '*'){
						$filtro_pessoa = str_replace('*','',$filtros["filtro_pessoa"]);
						$busca_array = explode(' ',$filtro_pessoa);
						foreach($busca_array as $busca_item){
							if(!empty(trim($busca_item)))
								$where .="  AND  (       remove_acentos(p.nome) LIKE '%".$busca_item."%' 
											  OR remove_acentos(p.apelido) LIKE '%".$busca_item."%' 
											  OR p.email LIKE '%".$busca_item."%' 
											  OR p.cpf_cnpj LIKE '%".$busca_item."%'
											   )  ";	
						}
				}
				else{
					$busca = trata_busca_sql_score($filtros["filtro_pessoa"]);
					if(isset($busca['multi'])){
						$where .= "  AND MATCH (p.nome,p.apelido,p.email,p.cpf_cnpj)  AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";			
					}
					else{
						$where .="  AND  (       remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
											  OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
											  OR p.email LIKE '%".$busca['simples']."%' 
											  OR p.cpf_cnpj LIKE '%".$busca['simples']."%'
											   )  ";	
					}
				}
					
			}
		}
		
		
		return $where;	
	}
	

	
	//------------------------------------------------------------------------------------------------
	function lista_tipo_acesso($pessoas="", &$conexao_BD_1){
	    $select = " select ta.*
					from tipo_acesso ta  ";
					
		$where = " WHERE 1=1 ";
		
		if($pessoas->tipo_acesso_id !=""){ $where .= " AND ta.id = ".$pessoas->tipo_acesso_id ; }
		
		$where .= " ORDER BY ta.descricao ASC ";	
		return $conexao_BD_1->query($select.$where);	
	}
	//------------------------------------------------------------------------------------------------
	function lista_status($condominios="", &$conexao_BD_1){
	    $select = " select s.*
					from status s  ";
					
		$where = " WHERE 1=1 ";
		
		if($condominios->status_id !=""){ $where .= " AND ta.id = ".$condominios->status_id ; }
		
		$where .= " ORDER BY s.descricao ASC ";	
		return $conexao_BD_1->query($select.$where);	
	}	
	//------------------------------------------------------------------------------------------------
	function lista_pessoas_ajax($busca, $tipo_pessoa="", &$conexao_BD_1){
		$select = " select p.* ";
		$from = " from pessoas p  ";	
		$where = " WHERE 1=1 ";
		
		$busca = trata_busca_sql_score($busca);
		if(isset($busca['multi'])){
			$select .= " ,MATCH (nome,apelido,email,cpf_cnpj) 
						AGAINST ('".$busca['multi']."' IN BOOLEAN MODE) AS  SCORE ";
			$where = " WHERE p.status_id=1 AND
						MATCH (nome,apelido,email,cpf_cnpj) 
						AGAINST ('".$busca['multi']."' IN BOOLEAN MODE)  ";
			$order = " ORDER BY SCORE DESC, p.nome 
						LIMIT 15 ";	
		}
		else{
			$where =" WHERE p.status_id=1 AND
							  (  remove_acentos(p.nome) LIKE '%".$busca['simples']."%' 
							  OR remove_acentos(p.apelido) LIKE '%".$busca['simples']."%' 
							  OR p.email LIKE '%".$busca['simples']."%' 
							  OR p.cpf_cnpj LIKE '%".$busca['simples']."%'
							   )  ";
			$order = "  ORDER BY	p.nome 	   LIMIT 15 ";	
		}	
							
		if($tipo_pessoa!=""){
				switch ($tipo_pessoa) {
					case 'compradores':		
						$where .= " AND p.eh_comprador = 'S' ";			
						break;	
					
					case 'vendedores':		
						$where .= " AND p.eh_vendedor = 'S' ";				
						break;	
					
					case 'leiloeiros':		
						$where .= " AND p.eh_leiloeiro = 'S' ";			
						break;	
					
					case 'user':		
						$where .= " AND p.eh_user= 'S' ";			
						break;	
				}
		}
		//echo $select.$from.$where.$order;
		return $conexao_BD_1->query($select.$from.$where.$order);		
	}
	
	function atribuir_haras($id_pessoa, $id_haras, &$conexao_BD_1){
		$update = " update haras set 
					proprietario_nome=null, proprietario_doc=null, proprietario_id = ".$id_pessoa."
					where 	id = ".$id_haras."   ";
		$conexao_BD_1->query_atualizacao($update);
	}
	
	function lista_by_cpf($cpf,$conexao_BD_1){
		
		$select = " select p.*,  s.descricao status_descricao,
					pf.id as perfil_id, pf.descricao as perfil_descricao, pf.dt_atualizacao as perfil_dt_atualizacao, 
					(select count(*) from haras h where h.proprietario_id = p.id  ) as total_haras
					";
		$select .= " from pessoas p  ";
		$select .= " left join status s ON p.status_id = s.id  ";
		$select .= " left join perfil pf ON pf.id = p.perfil_id  ";
		
		$select .= "   WHERE   REPLACE(REPLACE(REPLACE(cpf_cnpj,'.',''),'-',''),'/','')   = ".$cpf;
		return $conexao_BD_1->query($select);
	}
	
	
							
	
	

	
}

?>