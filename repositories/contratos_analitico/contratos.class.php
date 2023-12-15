<?php
class contratos{
	
	
	private $id 		   		 = ""; 
	private $descricao           = ""; 
	private $dt_contrato		 = ""; 
	private $vl_contrato		 = ""; 
	private $vendedor_id	   	 = ""; 
	private $comprador_id	 	 = "";
	private $eventos_id	 	   	 = "";
	private $vl_entrada	 	  	 = "";
	private $tp_contrato		 = "";
	private $nu_parcelas		 = "";
	private $pessoas_id_inclusao = "";
	private $dt_inclusao		 = "";
	private $honor_adimp		 = "";
	private $honor_inadimp       = "";
	private $status              = "";
	private $parcela_primeiro_pagto = "";
	private $juros				 = "";
	private $contratos_id_pai	 = "";
	private $dt_primeira_parcela = "";
	
	private $termo_percentual_contrato = "";
	private $termo_descricao_lote				 = "";
	private $termo_descricao_pagto	 = "";
	private $termo_local_data = "";
	private $termo_nomes_lote = "";

	private $tp_contrato_boleto = "";
	private $gerar_boleto       = "";
	private $desconto_total     = "";

	private $fl_parcelas_zerado = "N";
    private $dt_parcelas_zerado = "";
    private $motivo_zerado = "";
    private $observacao_zerado = "";

	private $dt_acao_judicial = "";
	
	private $suspenso = "";
	private $dt_suspensao = "";
	private $dt_retorno_suspensao = "";
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function prepare($acao) {
		
	   $string_sql = "";	
	   	
	   switch ($acao) {
			case 'update':	
				
				foreach ($this as $key => $value) {
				   if (($value != "") && ($key != "id")){
					   if ($value == 'NULL') {
						$string_sql .= " $key = NULL,";   
					   } else {
						$string_sql .= " $key = '".$value."',";   
					   }
				   }
				   //print "$key => $value\n";
			    }
				$string_sql = substr($string_sql,0,-1);			
				break;
			case 'insert':	
				
				$campos =  "(";
				$valores = "values ("; 
				
				foreach ($this as $key => $value) {
					$campos .=  " $key,";
					if ($key == "id"){
						$valores .= " NULL,";
					}
					else{
					    if ($value == "" || $value == "NULL"){
							$valores .= " NULL,";   
						}
						else{
					    	$valores .= " '".$value."',";   
						}  
					}
			    }
				$string_sql = substr($campos,0,-1).") ".substr($valores,0,-1).")";			
				break;
			case 'select':	
				
				foreach ($this as $key => $value) {
				   if (($value != "")){
						$string_sql .= " and $key = '".$value."' ";   
				   }
			    }
				$string_sql = substr($string_sql,0,-1);			
				break;
			case 'delete':	
				
				foreach ($this as $key => $value) {
				   if (($value != "")){
						$string_sql .= " and $key = '".$value."' ";   
				   }
			    }
				$string_sql = substr($string_sql,0,-1);			
				break;
	   }
       return $string_sql;
    }
}

?>