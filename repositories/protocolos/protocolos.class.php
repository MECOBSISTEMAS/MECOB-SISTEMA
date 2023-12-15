<?php
class protocolos{
	
	private $id 		   	    = ""; 
	private $protocolo          = ""; 
	private $dt_registro        = ""; 
	private $cad_pessoa         = ""; 
	private $vendedor           = ""; 
	private $vendedor_id        = ""; 
	private $comprador          = ""; 
	private $comprador_id       = ""; 
	private $evento             = ""; 
	private $evento_id          = ""; 
	private $produto            = ""; 
	private $valor              = ""; 
	private $dt_parcela         = ""; 
	private $nr_parcela         = ""; 
	private $prazo              = ""; 
	private $status             = ""; 
	private $setor              = ""; 
	private $setor_trans        = ""; 
	private $trans_pessoa       = ""; 
	private $finalizado         = ""; 
	private $finalizado_pessoa  = ""; 
	private $finalizado_motivo  = ""; 
	private $contrato_id        = ""; 
	private $enable             = 1; 
	private $observacao         = ""; 
	private $dt_contrato        = ""; 
	private $dt_digitalizado    = ""; 
	private $ct_verifica        = 0; 
	
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