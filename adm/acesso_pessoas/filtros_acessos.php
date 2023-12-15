<form id="form_acesso" action="javascript:filtrar_fields();" method="post" >
    <div class="mg-bt-10 row mg-lf--5">
        <div class=" input-group  input-group-sm fl-lf  col-sm-2  col-xs-11   mg-bt-3">
        <input name="filtro_data" id="filtro_data" type="text" class="form-control  pull-left" value="<?php echo date('d/m/Y'); ?>"  />
        </div>
        
        <div class=" input-group  input-group-sm fl-lf  col-sm-8">
<input type="text" name="filtro_nome" id="filtro_nome"    class="form-control " placeholder="separe os termos de busca, separados por vírgula ex.: 37224,contratos_id" >
</div> 

    
        <div class=" fl-lf col-lg-2  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i class="fa fa-history"></i></button>
            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>
            <button type="button" class="btn btn-success btn-sm mg-lf-3 fl-lf hidden" onclick="gerar_pdf_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha" >
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </button>
   	 </div>
     </div>
</form>
                                
