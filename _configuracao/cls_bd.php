<?php
/*
  Autor: Maur�cio Rosa (48)9957 3422
  Data: 20/08/2002
  Objetivo: Encapsular os acessos ao banco de Dados.
 */

//
class bancoDeDados {

//
//*****************************ATRIBUTOS DA CLASSE ************************
//
//************* Atributos para conex�o ************************************

    var $aHost;
    var $aUsuario;
    var $aSenha;
//
//***************** BD selecionado ****************************************

    var $aBanco;
//
//************ Atributos usados durante as consultas *********************

    var $aConexao;
    var $aSql;
    var $aRetorno;
    var $aNumeroRegistros;
    var $aRegistro;
    var $aPosicao;
    var $aUltimoId;

//
//
//**************************** CONSTRUTOR DA CLASSE BD ********************
    function __construct($pHost, $pUsuario, $pSenha, $pBanco = "") {

        $this->aHost = $pHost;
        $this->aUsuario = $pUsuario;
        $this->aSenha = $pSenha;
        $this->aBanco = $pBanco;
        $this->conectar();
    }
//*************************************************************************
//
//*************************** FUN��O DE SELE��O DO BD *********************
    function selecionaBanco($pBanco) {

        $this->aBanco = 'teiacard';
    }
//*************************************************************************
//
//************************* FUNCAO CONECTAR *******************************
    function conectar() {
        try {
            if (BD_TIPO_CONNECT == 'mysql') {
				$opcoes = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
				);
                $this->aConexao = new PDO('mysql:host=' . $this->aHost . ';dbname=' . $this->aBanco, $this->aUsuario, $this->aSenha, $opcoes);
            } else {
                $this->aConexao = new PDO('oci:dbname=' . $this->aHost.';charset=WE8MSWIN1252', $this->aUsuario, $this->aSenha, '');
            }
        } catch (PDOException $i) {
            print "Erro: <code>" . $i->getMessage() . "</code>";
        }
    }
//*************************************************************************
//
//************************** FUN��O QUERY *********************************
    function query($pSql) {
     
        $this->aSql = $pSql;
        $_SESSION['sql'] = $this->aSql;
	
		$this->aRetorno = $this->aConexao->query($this->aSql);		
		$this->aPosicao = 1;			
		$this->aRegistros = $this->aRetorno->fetchAll(PDO::FETCH_ASSOC);
		$this->aNumeroRegistros = count($this->aRegistros);
		$_SESSION['qtdPages'] = $this->aNumeroRegistros;			
		return $this->aRegistros;			
		
    }
//*************************************************************************
//
//************************** FUN��O QUERY *********************************
    function query_atualizacao($pSql) {

        $this->aSql = $pSql;

        $this->aConexao->exec($this->aSql);
        $erro = $this->aConexao->errorInfo();
				
        $sucesso = false;				
        foreach ($erro as $key => $value) {
            if ($key == 0) {
                if ($value == "00000") {
                   $sucesso = true;
                } else {
                   $sucesso = false;	
                }
            }
			if ($key == 1) {
                if (empty($value)) {
                   $sucesso = true;	
                } else {					
                   $sucesso = false;	
                }
            }
        }
		
		if ($sucesso) {
		   return $this->aRetorno = 1;
		} else {
			return $this->aRetorno = 0;
		}
    }
//*************************************************************************
//
//************************** FUN��O QUERY *********************************
    function query_inserir($pSql) {

        $this->aSql = $pSql;
        $this->aRetorno = $this->aConexao->exec($this->aSql);
		$erro = $this->aConexao->errorInfo();
		
		$sucesso = false;				
        foreach ($erro as $key => $value) {
            if ($key == 0) {
                if ($value == "00000") {
                   $sucesso = true;
                } else {
                   $sucesso = false;	
                }
            }
			if ($key == 1) {
                if (empty($value)) {
                   $sucesso = true;	
                } else {					
                   $sucesso = false;	
                }
            }
        }
		
		if ($sucesso) {
		   return $this->aUltimoId = $this->aConexao->lastInsertId();	
		   #return $this->aRetorno = 1;
		} else {
			return $this->aRetorno = 0;
		}

    }
//*************************************************************************
//
//*************************** FUN��O QTDADE DE REGISTROS ******************
    function numeroDeRegistros() {
        return $this->aNumeroRegistros;
    }
//*************************************************************************
//
//*************************** FUN��O LE REGISTRO **************************
    function leRegistro() {

        $this->aRegistrado = $this->aRegistros;
        $this->aPosicao++;
        $valor = (count($this->aRegistrado) > 0) ? array_change_key_case($this->aRegistrado[0]) : 0;
        return $valor;
    }
//*************************************************************************
//
//****** FUN��O RETORNA ULTIMO ID INSERIDO *******************************
    function retornaId() {
        return $this->aUltimoId;
    }
//*************************************************************************
    function listaRegistro() {
		echo "ATUALIZAR CÓDIGO LISTA REGISTRO DEVE RETORNAR NA QUERY";
		exit;
    }
//*************************************************************************
//
//
//*************************************************************************	
	function insert(&$object,$echo=0){
		
		$tabela = get_class($object);
		
		$insert = " insert into ".$tabela." ".$object->prepare("insert");
		if($echo) echo $insert;
		$this->query_inserir($insert);
		return $object->id = $this->retornaId();             
	}
//*************************************************************************
//
//*************************************************************************		
	function update($object,$echo=0){
		
		$tabela = get_class($object);			
				
		$update = " update ".$tabela." 
					set ".$object->prepare("update")."
					where id = ".$object->id;
		if($echo) echo $update;
		$this->query_atualizacao($update);
		return $this->aRetorno;			
	}
//*************************************************************************
//
//*************************************************************************	
//*************************************************************************
//
//*************************************************************************		
	function select($object){
		
		$tabela = get_class($object);			
				
		$select = " select *
					from ".$tabela." 
					where 1 = 1 ".$object->prepare("select");
		return $this->query($select);
	}
//*************************************************************************
//
//*************************************************************************
//*************************************************************************
//
//*************************************************************************
    function delete($object){

        $tabela = get_class($object);

        $delete = " delete from ".$tabela." 
					where 1=1 ".$object->prepare("delete");
        return $this->query_atualizacao($delete);
    }
//*************************************************************************
//
//*************************************************************************	
//*************************************************************************
//
//*************************************************************************			
	function getIp() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {

            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
//*************************************************************************
//
//*************************************************************************	
//*************************************************************************
//
//*************************************************************************	
	
	function ehAdmin($pessoa_id, &$conexao_BD_1){
		
		$select = " select * 
					from pessoas p
					join tipo_acesso ta on ta.id = p.tipo_acesso_id
					WHERE p.id = ".$pessoa_id."  and ta.descricao = 'ADMINISTRADOR'
		";
		$conexao_BD_1->query($select);	
		
		if ($conexao_BD_1->numeroDeRegistros() > 0){
			return true;	
		}
		else{
			return false;	
		}		
	}
	
	function ehMorador($pessoa_id,$condominio_id, &$conexao_BD_1){
		
		$select = " SELECT COUNT(*)  as total
					FROM tipo_pessoas_condominios tpc 
					JOIN unidades u ON u.id = tpc.unidades_id
					JOIN tipo_unidade tu ON u.tipo_unidade_id = tu.id
					JOIN condominios c ON u.condominios_id = c.id 
					JOIN tipo_pessoas tp ON tpc.tipo_pessoas_id = tp.id 
					WHERE tpc.pessoas_id = ".$pessoa_id." AND u.condominios_id = ".$condominio_id;

		$ret = $conexao_BD_1->query($select);	
		#print_r($ret);
		return $ret[0]['total'];	
	}
}
//fim da classe
?>