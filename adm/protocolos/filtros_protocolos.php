<form id="form_filtros_protocolos" name="form_filtros_protocolos" action="javascript:filtrar_fields();" method="post" autocomplete="off">
    <div class="mg-bt-10 row mg-lf--5">

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11 mg-bt-3">
            <input type="text" name="filtro_protocolo_id" id="filtro_protocolo_id" class="form-control" placeholder="Protocolo"
            <?php if(isset($_GET['protocolo']) && is_numeric($_GET['protocolo'])){ echo 'value="'.$_GET['protocolo'].'"'; $ini_filtro=1;}?>
            >
        </div> 

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_data" id="filtro_data" class="form-control" placeholder="Data Registro" >
        </div> 


        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_prazo" id="filtro_prazo" class="form-control" placeholder="Data Prazo" >
        </div> 

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select  name="filtro_setor" id="filtro_setor" class="form-control"  >
            <option value=""> Setor</option>
            <option value="Confirmação"> Confirmação</option>
            <option value="Contratos"> Contratos</option>
            <option value="Boletos"> Boletos</option>
            <option value="Jurídico"> Jurídico</option>
            <option value="Embriões"> Embriões</option>
            <option value="Advogados"> Advogados </option>
            </select>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select  name="filtro_status" id="filtro_status" class="form-control"  >
            <option value=""> Status</option>
            <option value="Cancelado"> Cancelado</option>
            <option value="Finalizado"> Finalizado</option>
            <option value="Pendente"> Pendente</option>
            </select>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <select  name="filtro_vencimento" id="filtro_vencimento" class="form-control"  >
            <option value=""> Prazo </option>
            <option value="a_vencer"> A Vencer</option>
            <option value="vencido"> Vencido</option>
            </select>
        </div>

        <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_vendedor" id="filtro_vendedor" class="form-control" placeholder="Vendedor" >
        </div> 

        <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_comprador" id="filtro_comprador" class="form-control" placeholder="Comprador" >
        </div> 

        <!-- <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_evento" id="filtro_evento" class="form-control" placeholder="Evento">
        </div> -->

        <div class=" input-group  input-group-sm fl-lf  col-lg-4 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
            <input type="text" name="filtro_produto" id="filtro_produto" class="form-control" placeholder="Produto">
        </div>


        <div class=" fl-lf col-lg-3  col-sm-11 col-md-2 col-xs-11  mg-bt-3 pd-0-0-0-5">
            <button type="button" class="btn btn-warning btn-sm  fl-lf" onclick="limpa_filtros();"><i class="fa fa-history"></i></button>

            <button type="submit" class="btn btn-danger btn-sm fl-lf mg-lf-5 " ><i class="fa fa-play"></i></button>

            <button type="button" class="btn btn-success btn-sm mg-lf-3 fl-lf " onclick="gerar_planilha_protocolos()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha" >
            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            </button>
            <!-- <button type="button" class="btn btn-info btn-sm mg-lf-3 fl-lf " onclick="gerar_pdf_lista()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar PDF" >
            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn   btn-sm mg-lf-3 fl-lf " onclick="gerar_planilha_vendedores()"  rel="tooltip" data-placement="bottom" data-html="true" data-original-title="Gerar Planilha"  style="background-color:#FF0; color:#999">
            <i class="fa fa-star" aria-hidden="true"></i>
            </button> -->
        </div>

    </div>
</form>
