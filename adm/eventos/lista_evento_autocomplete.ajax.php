<?php
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

if(isset($_REQUEST["palavra"])){
	$palavra = $_REQUEST["palavra"];
}
else{
	echo 0; 
	exit;
}

$tamanho_palavra = strlen($palavra);
if($tamanho_palavra>2){
	include_once(getenv('CAMINHO_RAIZ')."/repositories/eventos/eventos.db.php");
	$eventosDB  = new eventosDB();
	
	$eventos = $eventosDB->lista_eventos_ajax($palavra, $conexao_BD_1);
	$total_eventos = count($eventos);
	if($total_eventos==0){echo 0; exit;}
	else{ ?>
    	<div id="div_loading_autocp" class="row  hidden loading_something">
        <img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" />
        </div>
<?php	foreach($eventos as $evento){
	?>
			<div class="row autocp_row" onclick='escolhe_autocomplete_evento(<?php echo json_encode($evento);?>);'>
                <div class="col-xs-12  autocp_desc">
                <?php echo "<span class='autocp_nm'>".$evento['nome']."</span>";
					  if(!empty($evento['tipo'])){ echo "<br>".$evento['tipo'];}
					  if(!empty($evento['leiloeiro_nome'])){ echo "<br>Leiloeiro: ".$evento['leiloeiro_nome'];}
				?>
                </div>
            </div>
		<?php
        }
		
	}
}
else{
	echo 0; exit;
}


?>
