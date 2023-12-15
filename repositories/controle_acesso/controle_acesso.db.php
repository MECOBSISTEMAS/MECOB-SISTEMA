<?php
class controle_acessoDB{
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }	
	
	function lista_perfil( &$conexao_BD_1,  $filtros){
		
	    $select = " select * from perfil	 ";
		$where = " where 1=1 ";
		
		if($filtros!=""){
			if(!empty($filtros['filtro_perfil'])){ $where .= " and id = ".$filtros['filtro_perfil'];}
		}
		
		return $conexao_BD_1->query($select.$where);	
	}
	
	function lista_modulo( &$conexao_BD_1,  $filtros){
		
	    $select = " select * from modulo	 ";
		$where = " where 1=1 ";
		return $conexao_BD_1->query($select.$where);	
	}
	
	function lista_permissao( &$conexao_BD_1,  $filtros=""){
		
	    $select = " select pm.*, m.codigo from perfil_modulo pm
					join modulo m on m.id = pm.modulo_id
				  ";
		$where = " where 1=1 ";
		
		if($filtros!=""){
			if(!empty($filtros['filtro_perfil'])){ $where .= " and pm.perfil_id = ".$filtros['filtro_perfil'];}
		}
		#echo $select.$where;
		return $conexao_BD_1->query($select.$where);	
	}
	
	function inserir_perfil( &$conexao_BD_1,  $perfil){
		$insert = " insert into perfil ( descricao, dt_atualizacao) values ('". $perfil."', '".date("Y-m-d H:i:s")."')	 ";
		return $conexao_BD_1->query_inserir($insert);	
	}
	
	function remover_perfil( &$conexao_BD_1,  $id){
		$delete = "delete from perfil_modulo where perfil_id = ".$id;
		$conexao_BD_1->query_atualizacao($delete);
		
		$update = " update pessoas set perfil_id = 2 where perfil_id = ".$id;
		$conexao_BD_1->query_atualizacao($update);
		
		$delete = "delete from perfil where id = ".$id;
		return $conexao_BD_1->query_atualizacao($delete);
	}
	
	function seta_permissao( $conexao_BD_1, $modulo, $perfil, $permissao){
		$select = " select * from perfil_modulo where modulo_id = ".$modulo." and perfil_id = ".$perfil." ";
		$atual = $conexao_BD_1->query($select);
		#print_r($atual);
		if(count($atual)){
			$update = " update  perfil_modulo set ".$permissao." = 'S'
						where modulo_id = ".$modulo." and perfil_id = ".$perfil." ";
			$conexao_BD_1->query_atualizacao($update);
		}
		else{
			$insert = " insert into perfil_modulo (modulo_id, perfil_id,".$permissao." ) 
						values (". $modulo.",".$perfil.",'S')	 ";
			$conexao_BD_1->query_inserir($insert);
		}
		$update_perfil = " update  perfil  set dt_atualizacao = '".date('Y-m-d H:i:s')."' where id = ".$perfil." ";
		$conexao_BD_1->query_atualizacao($update_perfil);
	}
	
	function remove_permissao( $conexao_BD_1, $modulo, $perfil, $permissao){

		$update = " update  perfil_modulo set ".$permissao." = 'N'
					where modulo_id = ".$modulo." and perfil_id = ".$perfil." ";
		$conexao_BD_1->query_atualizacao($update);

		$update_perfil = " update  perfil  set dt_atualizacao = '".date('Y-m-d H:i:s')."' where id = ".$perfil." ";
		$conexao_BD_1->query_atualizacao($update_perfil);
	}	
	
}

?>