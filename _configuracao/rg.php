<?php

include_once("config.php");
include_once("cls_bd.php");

function register_globals_on() {

//    $bd_rg = new bancoDeDados(BD_HOST, BD_USUARIO, BD_SENHA, BD_BANCO);
//    $bd_rg->conectar();

    if ($_POST) {
        foreach ($_POST as $var => $valor) {
            global $$var;
            if (gettype($valor) == 'string'){
//                if (BD_TIPO_CONNECT == 'mysql') {
//                    $$var = mysql_real_escape_string(str_replace('"', "'", $valor));
//                } else {
                    $valor = str_replace('"', "",  stripslashes($valor));
				   $$var = str_replace("'", "",  $valor);
                } else
                $$var = $valor;
        }
    }

    if ($_GET) {
	
        foreach ($_GET as $var => $valor) {
		
            global $$var;
            if (gettype($valor) == 'string'){
//                if (BD_TIPO_CONNECT == 'mysql') {
//                    $$var = mysql_real_escape_string(str_replace('"', "'", $valor));
//                } else {
                     $valor = str_replace('"', "",  stripslashes($valor));
				   $$var = str_replace("'", "",  $valor);
                } else
                $$var = $valor;
        }
		
    }
    if ($_REQUEST) {
        foreach ($_REQUEST as $var => $valor) {
            global $$var;
            if (gettype($valor) == 'string'){
//                if (BD_TIPO_CONNECT == 'mysql') {
//                    $$var = mysql_real_escape_string(str_replace('"', "'", $valor));
//                } else {
                   $valor = str_replace('"', "",  stripslashes($valor));
				   $$var = str_replace("'", "",  $valor);
                } else
                $$var = $valor;
        }
    }
}

?>