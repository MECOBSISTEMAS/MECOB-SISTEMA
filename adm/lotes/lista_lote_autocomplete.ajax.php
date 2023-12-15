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
	include_once(getenv('CAMINHO_RAIZ')."/repositories/lotes/lotes.db.php");
	$lotesDB  = new lotesDB();
	
	$lotes = $lotesDB->lista_lotes_ajax($palavra, $conexao_BD_1);
	$total_lotes = count($lotes);
	if($total_lotes==0){echo 0; exit;}
	else{ ?>
    	<div id="div_loading_autocp" class="row  hidden loading_something">
        <img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" />
        </div>
<?php	foreach($lotes as $lote){
	?>
			<div class="row autocp_row" onclick='escolhe_autocomplete_lote(<?php echo json_encode($lote);?>);'>
                <div class="col-xs-12  autocp_desc">
                <?php echo "<span class='autocp_nm'>".$lote['nome']."</span>";
					  if(!empty($lote['num_registro'])){ echo "<br>".$lote['num_registro'];}
					  if(!empty($lote['tipo'])){ echo "<br>".$lote['tipo'];}
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
