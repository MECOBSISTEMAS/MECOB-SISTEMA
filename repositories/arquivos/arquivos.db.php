<?php
class arquivosDB{
			
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_arquivos($arquivos, &$conexao_BD_1, $filtros = "", $order = "", $inicial =0,$limit=30){
		$where 	 = " where 1 = 1 ";
		
		$select =" select *
		           from arquivos a
		";
		
		$where .= $this->retorna_where_lista_arquivos($filtros, $arquivos);
		
		$orderby = " ORDER BY ";
		if($order != ""){
			$orderby.=$order;
		}
		$orderby .= "  a.id asc  ";
		
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
	
	function lista_totais_arquivos($filtros, &$conexao_BD_1){
		$where 	 = " where 1 = 1 ";
				
		$select = " select count(a.id) total_arquivos
					from arquivos a ";
					
		$where .= $this->retorna_where_lista_arquivos($filtros);
		//echo $select.$where;
		$conexao_BD_1->query($select.$where);	
		$reg = $conexao_BD_1->leRegistro();
		return $reg["total_arquivos"];
	}
	
	function retorna_where_lista_arquivos($filtros, $arquivos=''){
					
		$where = " ";
		
		if($arquivos!=''){
			if($arquivos->id !=""){ $where .= " AND a.id = ".$arquivos->id ; }
            if($arquivos->tp_arq !=""){ $where .= " AND a.tp_arq = '".$arquivos->tp_arq."'" ; }

		}
				
		if($filtros!=""){

            if(!empty($filtros["filtro_arquivos"])){
                $where .= "  AND a.nm_arq like '%".$filtros["filtro_arquivos"]."%' ";
            }

            if(!empty($filtros["filtro_dt_arquivo"])){
                $where .= "  AND date(a.dt_arq) = '".ConverteData($filtros["filtro_dt_arquivo"])."' ";
            }

            if(!empty($filtros["filtro_tp_arquivo"])){
                $where .= "  AND a.tp_arq =  ('".$filtros["filtro_tp_arquivo"]."') ";
            }

            if(!empty($filtros["filtro_status_arquivo"])){
                $where .= "  AND a.status in  (".$filtros["filtro_status_arquivo"].") ";
            }

            if(!empty($filtros["filtro_origem"])){
                $where .= "  AND a.origem =  '".$filtros["filtro_origem"]."' ";
            }


		}
		
		
		return $where;
		
	}
			
}

?>