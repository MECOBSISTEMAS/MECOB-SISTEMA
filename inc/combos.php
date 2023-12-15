<?php

function combo_tipo_arquivo($name, $id, $class, $valor_combo = "",$firstValue = 'Selecione o Tipo'){ ?>
	<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>"  >
        <option value=""><?php echo $firstValue;?></option>
        <option value="RETORNO_BOLETO" > RETORNO BOLETO</option>
        <option value="RETORNO_TED"> RETORNO TED</option>
    </select>
<?php
}

function combo_tipo_lote($name, $id, $class, $valor_combo = "",$firstValue = 'Selecione o Tipo'){ ?>
	<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>"  >
        <option value=""><?php echo $firstValue;?></option>
        <option value="Cobertura" > Cobertura</option>
        <option value="Embrião"> Embrião</option>
        <option value="Fêmea"> Fêmea</option>
        <option value="Macho"> Macho</option>
        <option value="Óvulo"> Óvulo</option>
        <option value="Outros"> Outro</option>
    </select>
<?php
}

function combo_tipo_evento($name, $id, $class, $valor_combo = "",$firstValue = 'Selecione o Tipo'){ ?>
	<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>"  >
        <option value=""><?php echo $firstValue;?></option>
        <option value="Online" > Online</option>
        <option value="Presencial"> Presencial</option>
        <option value="Virtual"> Virtual</option>
        <option value="Outros"> Outro</option>
    </select>
<?php
}


function combo_perfil($name, $id, $class, $valor_combo = "",$firstvalue = "Perfil") { 
	 global $username, $senha;
	$lista_tipo_acesso = file_get_contents(getenv('CAMINHO_SITE')."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=lista_perfil", false, HeaderToFileGetContent($username,$senha));
	$lista_tipo_acesso = json_decode($lista_tipo_acesso,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>">
        <option value=""><?php echo $firstvalue;?></option>
       	<?php
        	foreach($lista_tipo_acesso as $tipo_acesso){ 
		?>
        		<option value="<?php echo $tipo_acesso['id']; ?>" <?php if ($valor_combo == $tipo_acesso['id']) { echo "selected"; } ?>>
                 <?php echo $tipo_acesso['descricao']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_tipo_acesso($name, $id, $class, $valor_combo = "") { 
	 global $username, $senha;
	$lista_tipo_acesso = file_get_contents(getenv('CAMINHO_SITE')."/repositories/pessoas/pessoas.ctrl.php?acao=listar_tipo_acesso", false, HeaderToFileGetContent($username,$senha));
	$lista_tipo_acesso = json_decode($lista_tipo_acesso,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>">
        <option value="">::</option>
       	<?php
        	foreach($lista_tipo_acesso as $tipo_acesso){ 
		?>
        		<option value="<?php echo $tipo_acesso['id']; ?>" <?php if ($valor_combo == $tipo_acesso['id']) { echo "selected"; } ?>>
                 <?php echo $tipo_acesso['descricao']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_status($name, $id, $class, $valor_combo = "",$first_value='Selecione') { 
	 global $username, $senha;
	$lista_status = file_get_contents(getenv('CAMINHO_SITE')."/repositories/pessoas/pessoas.ctrl.php?acao=listar_status", false, HeaderToFileGetContent($username,$senha));
	$lista_status = json_decode($lista_status,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class; ?>">
        <option value=""><?php echo $first_value;?></option>
       	<?php
        	foreach($lista_status as $status){ 
		?>
        		<option value="<?php echo $status['id']; ?>" <?php if ($valor_combo == $status['id']) { echo "selected"; } ?>>
                 <?php echo $status['descricao']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_categorias_produto($name, $id, $class, $valor_combo = "",$first_value='Selecione',$multiple='0') { 
	 global $username, $senha;
	$lista_categorias_produto = file_get_contents(getenv('CAMINHO_SITE')."/repositories/categorias_produto/categorias_produto.ctrl.php?acao=listar", false, HeaderToFileGetContent($username,$senha));
	$lista_categorias_produto = json_decode($lista_categorias_produto,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?><?php if($multiple)echo '[]';?>" class="<?php echo $class;   if($multiple){echo 'multiselect" multiple="multiple';}?>    " >
       <?php if(!$multiple){ echo ' <option value="">'.$first_value.'</option>[]'; } ?>
       	<?php
        	foreach($lista_categorias_produto as $categorias_produto){ 
		?>
        		<option value="<?php echo $categorias_produto['id']; ?>" <?php if ($valor_combo == $categorias_produto['id']) { echo "selected"; } ?>>
                 <?php echo $categorias_produto['descricao']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_categorias_caixa($name, $id, $class, $valor_combo = "",$first_value='Selecione',$multiple='0',$show_fixos=1) { 
	 global $username, $senha;
	$lista_categorias_lancamento = file_get_contents(getenv('CAMINHO_SITE')."/repositories/categorias_lancamento/categorias_lancamento.ctrl.php?acao=listar", false, HeaderToFileGetContent($username,$senha));
	$lista_categorias_lancamento = json_decode($lista_categorias_lancamento,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?><?php if($multiple)echo '[]';?>" class="<?php echo $class;   if($multiple){echo 'multiselect" multiple="multiple';}?>    " >
       <?php if(!$multiple){ echo ' <option value="">'.$first_value.'</option>[]'; } ?>
		<?php
        	foreach($lista_categorias_lancamento as $categorias_lancamento){ 
				if(!$show_fixos){ if($categorias_lancamento['fixa']!='N')continue; }
		?>
        		<option value="<?php echo $categorias_lancamento['id']; ?>" <?php if ($valor_combo == $categorias_lancamento['id']) { echo "selected"; } ?>>
                 <?php echo $categorias_lancamento['tipo'].' - '.$categorias_lancamento['descricao']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}


function combo_fornecedores($name, $id, $class, $valor_combo = "",$first_value="Fornecedores",$multiple=1) { 
	 global $username, $senha;
	$lista_fornecedores = file_get_contents(getenv('CAMINHO_SITE')."/repositories/pessoas/pessoas.ctrl.php?acao=listar&tipo_pessoa=fornecedores&limit=N", false, HeaderToFileGetContent($username,$senha));
	$lista_fornecedores = json_decode($lista_fornecedores,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?><?php if($multiple)echo '[]';?>" class="<?php echo $class;    if($multiple){echo 'multiselect" multiple="multiple';}?>    " >
       	<?php if(!$multiple){ echo ' <option value="">'.$first_value.'</option>[]'; } ?>
		<?php
        	foreach($lista_fornecedores as $fornecedor){ 
		?>
        		<option value="<?php echo $fornecedor['id']; ?>" <?php if ($valor_combo == $fornecedor['id']) { echo "selected"; } ?>>
                 <?php echo $fornecedor['nome']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_vendedores($name, $id, $class, $valor_combo = "",$first_value="Vendedores") { 
	 global $username, $senha;
	$lista_vendedores = file_get_contents(getenv('CAMINHO_SITE')."/repositories/pessoas/pessoas.ctrl.php?acao=listar&tipo_pessoa=vendedores&limit=N", false, HeaderToFileGetContent($username,$senha));
	$lista_vendedores = json_decode($lista_vendedores,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>[]" class="<?php echo $class; ?> multiselect" multiple="multiple" >
       <!-- <option value=""><?php #echo $first_value;?></option>-->
       	<?php
        	foreach($lista_vendedores as $vendedor){ 
		?>
        		<option value="<?php echo $vendedor['id']; ?>" <?php if ($valor_combo == $vendedor['id']) { echo "selected"; } ?>>
                 <?php echo $vendedor['nome']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_clientes($name, $id, $class, $valor_combo = "",$first_value="Clientes") { 
	 global $username, $senha;
	$lista_clientes = file_get_contents(getenv('CAMINHO_SITE')."/repositories/pessoas/pessoas.ctrl.php?acao=listar&tipo_pessoa=clientes&limit=N", false, HeaderToFileGetContent($username,$senha));
	$lista_clientes = json_decode($lista_clientes,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>[]" class="<?php echo $class; ?> multiselect" multiple="multiple" >
        <!--<option value=""><?php #echo $first_value;?></option>-->
       	<?php
        	foreach($lista_clientes as $cliente){ 
		?>
        		<option value="<?php echo $cliente['id']; ?>" <?php if ($valor_combo == $cliente['id']) { echo "selected"; } ?>>
                 <?php echo $cliente['nome']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}

function combo_produtos($name, $id, $class, $valor_combo = "",$first_value="Produtos") { 
	 global $username, $senha;
	$lista_produtos = file_get_contents(getenv('CAMINHO_SITE')."/repositories/produtos/produtos.ctrl.php?acao=listar&limit=N", false, HeaderToFileGetContent($username,$senha));
	$lista_produtos = json_decode($lista_produtos,true);
?>	
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>[]" class="<?php echo $class; ?> multiselect" multiple="multiple" >
        <!--<option value=""><?php #echo $first_value;?></option>-->
       	<?php
        	foreach($lista_produtos as $produto){ 
		?>
        		<option value="<?php echo $produto['id']; ?>" <?php if ($valor_combo == $produto['id']) { echo "selected"; } ?>>
                 <?php echo $produto['codigo']; ?> - <?php echo $produto['nome']; ?> - <?php echo $produto['nome_fornecedor']; ?>
                </option>
        <?php
			}
		?>
    </select>
<?php
}