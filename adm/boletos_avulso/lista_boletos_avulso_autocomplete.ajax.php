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
	include_once(getenv('CAMINHO_RAIZ')."/repositories/haras/haras.db.php");
	$harasDB  = new harasDB();
	
	$haras = $harasDB->lista_haras_ajax($palavra, $conexao_BD_1);
	$total_haras = count($haras);
	if($total_haras==0){echo 0; exit;}
	else{ ?>
    	<div id="div_loading_autocp" class="row  hidden loading_something">
        <img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" />
        </div>
<?php	foreach($haras as $hara){
	?>
			<div class="row autocp_row" onclick='escolhe_autocomplete_haras(<?php echo json_encode($hara);?>);'>
                <div class="col-xs-12  autocp_desc">
                <?php echo "<span class='autocp_nm'>".$hara['nome']."</span>";
					  if(!empty($hara['telefone']) && $hara['telefone']!='null'){ echo "<br>".$hara['telefone'];}
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
