<form id="form_actualizar_cuenta">
    <div class="modal fade" id="modal_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title" id="exampleModalLabel">EDITAR CUENTA : <span id="id_cuenta"></span></h5>
                    <input type="hidden" name="id" value="" id="ce_id"/>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                                <div class="mb-3 mt-2 ms-2 me-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ce_nivel" id="cen_rubro" value="R" onchange="cambio_nivel('ce')" checked>
                                        <label class="form-check-label" for="cen_rubro">Rubro</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ce_nivel" id="cen_titulo" value="T" onchange="cambio_nivel('ce')">
                                        <label class="form-check-label" for="cen_titulo">Título</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ce_nivel" id="cen_compuesta" value="C" onchange="cambio_nivel('ce')">
                                        <label class="form-check-label" for="cen_compuesta">Compuesta</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ce_nivel" id="cen_subcuenta" value="S" onchange="cambio_nivel('ce')">
                                        <label class="form-check-label" for="cen_subcuenta">Sub Cuenta</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" id="ce_lista_grupo">
                                
                            </div>
                            <div class="col-12" id="ce_lista_rubro">
                                
                            </div>
                            <div class="col-12" id="ce_lista_titulo">
                                
                            </div>
                            <div class="col-12" id="ce_lista_compuesta">
                                
                            </div>
                            <label for="" class="form-label text-muted"><b>Cuenta Contable:</b></label>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" name="codigo_cuenta" id="ce_codigo_cuenta" class="form-control-plaintext" required readonly/>
                                    <label for="ce_codigo_cuenta">Código Cuenta</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="ce_codigo" type="text" name="codigo" class="form-control" minlength="1" required autocomplete="off" onblur="generar_codigo('ce')"/>
                                    <label for="ce_codigo" id="label_ce_codigo"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" id="ce_descripcion" name="descripcion" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="ce_descripcion">Nombre</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise me-2"></i>ACTUALIZAR</button>
                </div>
            </div>
        </div>
    </div>
</form>