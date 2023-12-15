<?php
class emailDB{
	
	public function __set($atrib, $value){
		$this->$atrib = $value;
    }
  
    public function __get($atrib){
        return $this->$atrib;
    }
	
	function listaemail(&$conexao_BD_1){
		$select = "select sm.*,
					(select count(id) from send_mail_destinatarios smd where smd.send_mail_id = sm.id and enviado = 0 ) aberto,
					(select count(id) from send_mail_destinatarios smd where smd.send_mail_id = sm.id and enviado > 0 ) enviado
				   from send_mail sm ";
		return  $conexao_BD_1->query($select);
	}
	
	function lista_emails_clientes(&$conexao_BD_1){
		$select = "select distinct email from empresa";
		return  $conexao_BD_1->query($select);
	}
	

    function next_send_mail(&$conexao_BD_1){
		$select_min = "select sm.id as send_mail_id, sm.*
					   from send_mail_destinatarios s 
					   join send_mail sm on sm.id = s.send_mail_id
					   where enviado = 0 
					   order by sm.prioridade desc, s.id asc
					   limit 1";
		return  $ret_sen_min = $conexao_BD_1->query($select_min);
	}
		
	function lista_send_mail(&$conexao_BD_1, $send_mail_id){
		
		$select = " select smd.* 
					from send_mail_destinatarios smd  
					where send_mail_id = ".$send_mail_id." and enviado = 0  
					order by smd.id asc LIMIT 20
				  ";
					
		return $conexao_BD_1->query($select);	
	}
	
	function confirma_send_mail(&$conexao_BD_1, $confirma_envio){
		$update = " update send_mail_destinatarios set enviado = 1 where id in (".$confirma_envio.")  ";
		$conexao_BD_1->query_atualizacao($update);	
		return 1;
	}
	

	
	function enviado(&$conexao_BD_1, $condominio_id, $pessoa_id){
		$update = "update send_mail set enviado = 1 
					where condominios_id = ".$condominio_id."  and pessoas_id= ".$pessoa_id." ";
		$conexao_BD_1->query_atualizacao($update);	
		return 1;
		
	}
	
	function insert_send_mail(&$conexao_BD_1, $destinatarios_lista, $assunto, $mensagem, $email_dest, $nome_dest, $email_reply, $nome_reply, $prioridade=0){
		if(count($destinatarios_lista)==0){return 0;}
		
		$insert = " insert into send_mail 
						(assunto, mensagem, email_dest, nome_dest, email_reply, nome_reply, prioridade)
						values
						('".base64_encode($assunto)."', '".base64_encode($mensagem)."', '".$email_dest."', '".$nome_dest."', '".$email_reply."', '".$nome_reply."' , ".$prioridade.") 
				  ";
		$send_mail_id =  $conexao_BD_1->query_inserir($insert);		
		
		$insert_smd = " insert into send_mail_destinatarios 
						(send_mail_id, email, enviado)
						values ";
		$cont_smd=0;
		foreach($destinatarios_lista as $dests_lista){
				if($cont_smd>0){$insert_smd .= ", ";}
				
				if(substr($dests_lista,0,10) == 'sem_email@'){
					continue;
				}
				
				$insert_smd .= " (".$send_mail_id.", '".$dests_lista."', 0)   ";
				$cont_smd++;
		}
		if($cont_smd)					
			$conexao_BD_1->query_inserir($insert_smd);
		
	}
	
}

?>