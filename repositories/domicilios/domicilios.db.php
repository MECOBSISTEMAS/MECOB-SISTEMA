<?php
class domiciliosDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	

	
	function lista_domicilios(  &$conexao_BD_1,  $filtros = "" , $order = "" , $inicial = 0,$limit=30){
		
		
		// 
		
		$select =" select banco, agencia, dv_agencia, conta, dv_conta, pv.nome as vendedor_nome 
				   from teds t
				   join pessoas pv on pv.id = t.pessoas_id_vendedor 
		 "; 
				 
		$where  = " where del_domc_bancario is null    ";
		$where .= $this->retorna_where_lista_domicilios( $filtros); 	
		
		$orderby = " group by  banco, agencia, dv_agencia, conta, dv_conta, pv.nome  ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  t.id asc  ";
		
		if ($limit == "N"){
			$limite = "";
		}
		else{	
			if ($limit == ""){
				$limit = 30;
			}	
			$limite = " LIMIT $inicial, $limit  ";
		}
		
		//echo $select.$where.$orderby.$limite;
		return $conexao_BD_1->query($select.$where.$orderby.$limite);	
	}
	
	function lista_totais_domicilios($filtros, &$conexao_BD_1 ){ 

		$select =" select count(*) total_domc from (
		           select banco, agencia, dv_agencia, conta, dv_conta, pv.nome
				   from teds t
				   join pessoas pv on pv.id = t.pessoas_id_vendedor  
		 "; 
		 
		 $select  .= " where del_domc_bancario is null     ";
		$select .= $this->retorna_where_lista_domicilios( $filtros); 
		 
		$select .= " group by  banco, agencia, dv_agencia, conta, dv_conta, pv.nome ) tab   ";
		//echo $select; 
		$retorno = $conexao_BD_1->query($select);	
		return $retorno;
		
	}
	
	function retorna_where_lista_domicilios($filtros){	
		$where = " ";

				
		if($filtros!=""){  
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
		
		return $where;	
	}
	

	
}