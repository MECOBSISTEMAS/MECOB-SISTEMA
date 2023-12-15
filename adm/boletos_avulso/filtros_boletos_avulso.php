<form id="form_filtros_boletos_avulso" action="javascript:filtrar_fields();" method="post">
<div class="mg-bt-10 row mg-lf--5">

<div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3 
<?php if($ehCliente){  echo 'hidden'; $ini_filtro=1; }  ?>
 "
>
<input type="text" name="filtro_proprietario" id="filtro_proprietario" class="form-control" placeholder="Proprietário (Nome, email ou documento)" 
<?php if($ehCliente){  echo 'value="'.$user_id.'"'; }  ?>
>
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_data" id="filtro_data" class="form-control" placeholder="Vencimento Inicial" >
</div> 

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<input type="text" name="filtro_data_fim" id="filtro_data_fim" class="form-control" placeholder="Vencimento Final" >
</div>  

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
<select name="filtro_status" id="filtro_status"    class="form-control " >
<option value="">Status Boleto</option>
<option value="1"   >Atrasado</option>
<option value="2"  >A vencer</option>
<option value="3"  >Liquidado</option>  
</select>
</div> 


<div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
<button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i class="fa fa-history"></i></button>
<button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>
</button>
</div>

</div>
</form>