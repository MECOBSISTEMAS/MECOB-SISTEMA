<?php
//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_REQUEST["palavra"]) &&  isset($_REQUEST["tipo_pessoa"])){
	$tipo_pessoa = $_REQUEST["tipo_pessoa"];
	$palavra = $_REQUEST["palavra"];
}
else{
	echo 0; 
	exit;
}

$tamanho_palavra = strlen($palavra);
if($tamanho_palavra>2){
	include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.db.php");
	$pessoasDB  = new pessoasDB();
	$pessoas = $pessoasDB->lista_pessoas_ajax($palavra, $tipo_pessoa, $conexao_BD_1);

	$total_pessoas = count($pessoas);
	if($total_pessoas==0){echo 0; exit;}
	else{ ?>
    	<div id="div_loading_autocp" class="row  hidden loading_something">
        <img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" />
        </div>
<?php	foreach($pessoas as $pessoa){
			if(empty($pessoa['foto']) || !file_exists(getenv('CAMINHO_RAIZ')."/imagens/fotos/nail/".$pessoa['foto'])){
				$pessoa['foto'] = "default.png";
			}
	?>
			<div class="row autocp_row" onclick='escolhe_autocomplete_pessoa(<?php echo json_encode($pessoa);?>,"<?php echo $tipo_pessoa;?>");'>
            	<div class="col-xs-4  col-sm-2 col-md-3 col-lg-2 autocp_foto max-wd-90 pd-0" >
                <img src="<?php echo img("/imagens/fotos/nail/",$pessoa['foto']); ?>" alt="" class="img-responsive img-circle "/>
                </div>
                <div class="col-xs-8 col-sm-10 col-md-9 col-lg-10 autocp_desc">
                <?php echo "<span class='autocp_nm'>".$pessoa['nome']."</span>";
					  if(!empty($pessoa['cpf_cnpj'])){ echo "<br>".Format($pessoa['cpf_cnpj'],'documento');}
					  if(!empty($pessoa['email'])){ echo "<br>".$pessoa['email'];}
						 
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
