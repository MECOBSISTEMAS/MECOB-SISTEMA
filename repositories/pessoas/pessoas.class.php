<?php
class pessoas{
	
	
	private $id 			     = ""; 
	private $nome 			   = ""; 
	private $dt_nascimento 	  = ""; 
	private $cpf_cnpj 		   = ""; 
	private $nacionalidade      = ""; 
	private $rg 			     = ""; 
	private $email			  = ""; 
	private $password 		   = ""; 
	private $saltdb 		     = ""; 
	private $dt_ativo 		   = ""; 
	private $apelido 		    = ""; 
	private $dt_inclusao 	    = "";
	private $rua 	 		    = "";
	private $numero 	 	     = "";
	private $complemento 	    = "";
	private $bairro 	 	     = "";
	private $cidade 	         = "";
	private $estado 	 	     = "";
	private $cep 	 	 	    = "";
	private $celular		    = "";
	private $site			   = "";
	private $facebook		   = "";
	private $twitter		    = "";
	private $sobre			  = "";
	private $foto			   = "";
	private $status_id		  = "";
	private $eh_user		    = "";
	#private $eh_admin		   = "";
	private $eh_vendedor	    = "";
	private $eh_comprador	   = "";
	private $eh_leiloeiro	   = "";
	private $perfil_id		  = "";
	private $supervisor		  = "";
	private $operador		  = "";
	
	private $telefone	  	   = "";
	private $contato		    = "";
	
	private $honor_adimp		="";
	private $honor_inadimp      ="";
	
	
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
						$string_sql .= " $key = '".$value."',";   
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
					    if ($value == ""){
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
	   }
       return $string_sql;
    }
}

?>