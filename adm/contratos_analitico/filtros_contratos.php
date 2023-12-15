<form id="form_filtros_contratos" action="javascript:filtrar_fields();" method="post">
    <div class="mg-bt-10 row mg-lf--5">

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_data" id="filtro_data" class="form-control" placeholder="Data Inicial">
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_data_fim" id="filtro_data_fim" class="form-control"
                placeholder="Data Final">
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_vendedor" id="filtro_vendedor" class="form-control"
                placeholder="Vendedor (Nome, email ou documento)">
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-3 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_comprador" id="filtro_comprador" class="form-control"
                placeholder="Comprador (Nome, email ou documento)">
        </div>
        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select  name="filtro_tipo" id="filtro_tipo" class="form-control"  >
            <option value=""> Tipo do Contrato</option>
            <option value="adimplencia"> Adimplência</option>
            <option value="inadimplencia"> Inadimplência</option>
            <option value="suspenso"> Suspenso</option>
            </select>
        </div>  

        <div class=" fl-lf col-lg-3  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i
                    class="fa fa-history"></i></button>
            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 "><i class="fa fa-play"></i></button>
            <button type="button" class="btn btn-info btn-sm mg-lf-3 fl-lf " onclick="gerar_pdf_lista()" rel="tooltip"
                data-placement="bottom" data-html="true" data-original-title="Gerar PDF">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </button>
        </div>

    </div>
</form>