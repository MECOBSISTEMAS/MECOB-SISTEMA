<form id="form_filtros_controle" action="javascript:filtrar_fields();" method="post">
<div class="mg-bt-10 row mg-lf--5">

<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-2 col-xs-11   mg-bt-3">
<?php echo combo_perfil('filtro_perfil', 'filtro_perfil', 'form-control  ', '');?> 
</div> 

<div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
<a href="<?php echo $link;?>/controle_acesso"> <button type="button" class="btn btn-warning btn-sm  fl-lf" ><i class="fa fa-refresh"></i></button></a>
<button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>
<button type="button" class="btn btn-success btn-sm mg-lf-3 fl-lf hidden" onclick="gerar_pdf_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha" >
<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
</button>
</div>

</div>
</form>