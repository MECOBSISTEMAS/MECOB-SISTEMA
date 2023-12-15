<?php
$legenda_colors = array();
$legenda_colors[] = array('cor'=>'#5F9EA0','desc'=>'A vencer','group'=>'confirmados');
$legenda_colors[] = array('cor'=>'#1BA261','desc'=>'Liquidados','group'=>'confirmados');
$legenda_colors[] = array('cor'=>'#FF5759','desc'=>'Atrasados','group'=>'confirmados');
$legenda_colors[] = array('cor'=>'#999','desc'=>'Suspensos','group'=>'confirmados');

$legenda_colors[] = array('cor'=>'#00A8E7','desc'=>'Em Acordo','group'=>'pendentes');
$legenda_colors[] = array('cor'=>'#337AB7','desc'=>'Parcialmente em acordo','group'=>'pendentes');
$legenda_colors[] = array('cor'=>'#FF5759','desc'=>'Pendentes','group'=>'pendentes');
$legenda_colors[] = array('cor'=>'#999','desc'=>'Suspensos','group'=>'pendentes');

$legenda_colors[] = array('cor'=>'#F0AD4E','desc'=>'Virou Inadimplente','group'=>'virou_inadimplente');
$legenda_colors[] = array('cor'=>'#999','desc'=>'Suspensos','group'=>'virou_inadimplente');

$legenda_colors[] = array('cor'=>'#3C3C3C','desc'=>'Ação Judicial','group'=>'acao_judicial');
$legenda_colors[] = array('cor'=>'#999','desc'=>'Suspensos','group'=>'acao_judicial');
?>
<div id='legenda_colors' style=" clear:both; float:right; width:200px">
	<?php
	foreach($legenda_colors as $legenda_cor){
	?>
    <div style="height:20px" class="row ctgp ctgp_<?php echo $legenda_cor['group'];   if($legenda_cor['group']!='confirmados'){echo ' hidden';}?> ">
    	<div class="col-xs-1" style="height:18px; background-color:<?php echo $legenda_cor['cor'];?>">
    	</div>
        <div class="col-xs-10">
        <?php echo $legenda_cor['desc'];?>
    	</div>
    </div>
	<?php
	}	
	?> 
</div>
<div class="cb"><br></div>

