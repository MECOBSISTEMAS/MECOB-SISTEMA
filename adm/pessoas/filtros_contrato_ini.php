<?php
if(isset($_POST["filtro_contrato"])){$filtros['filtro_contrato'] = trim($_POST["filtro_contrato"]);}
if(isset($_POST["filtro_data"])){$filtros['filtro_data'] = trim($_POST["filtro_data"]);}
if(isset($_POST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_POST["filtro_data_fim"]);} 
if(isset($_POST["filtro_status"])){$filtros['filtro_status'] = trim($_POST["filtro_status"]);}
if(isset($_POST["filtro_evento"])){$filtros['filtro_evento'] = trim($_POST["filtro_evento"]);}

if($cfg_filtros == 'compras'){ 
	if(isset($_POST["filtro_vendedor"])){$filtros['filtro_vendedor'] = '* '.trim($_POST["filtro_vendedor"]);}
}
else{
	if(isset($_POST["filtro_comprador"])){$filtros['filtro_comprador'] = '* '.trim($_POST["filtro_comprador"]);} 
}

?>