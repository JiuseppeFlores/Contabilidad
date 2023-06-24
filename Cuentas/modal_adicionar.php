<form id="form_adicionar_cuenta">
    <div class="modal fade" id="modal_adicionar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="exampleModalLabel">ADICIONAR CUENTA</h5>
                </div>
                <div class="modal-body" tabindex="-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                                <div class="mb-3 mt-2 ms-2 me-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ca_nivel" id="can_rubro" value="R" onchange="cambio_nivel('ca')" checked>
                                        <label class="form-check-label" for="can_rubro">Rubro</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ca_nivel" id="can_titulo" value="T" onchange="cambio_nivel('ca')">
                                        <label class="form-check-label" for="can_titulo">Título</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ca_nivel" id="can_compuesta" value="C" onchange="cambio_nivel('ca')">
                                        <label class="form-check-label" for="can_compuesta">Compuesta</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ca_nivel" id="can_subcuenta" value="S" onchange="cambio_nivel('ca')">
                                        <label class="form-check-label" for="can_subcuenta">Sub Cuenta</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" id="ca_lista_grupo">
                                
                            </div>
                            <div class="col-12" id="ca_lista_rubro">
                                
                            </div>
                            <div class="col-12" id="ca_lista_titulo">
                                
                            </div>
                            <div class="col-12" id="ca_lista_compuesta">
                                
                            </div>
                            <label for="" class="form-label text-muted"><b>Cuenta Contable:</b></label>
                            <div class="col-12">
                                
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" name="codigo_cuenta" id="ca_codigo_cuenta" class="form-control-plaintext" required readonly/>
                                    <label for="ca_codigo_cuenta">Código Cuenta</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="ca_codigo" type="text" name="codigo" class="form-control" minlength="1" required autocomplete="off" onblur="generar_codigo('ca')"/>
                                    <label for="ca_codigo" id="label_ca_codigo"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="ca_descripcion" type="text" name="descripcion" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="ca_descripcion">Nombre</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="ca_movimiento" name="movimiento">
                                    <label class="form-check-label" for="ca_movimiento">
                                        Movimiento
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-plus me-2"></i>ADICIONAR</button>
                </div>
            </div>
        </div>
    </div>
</form>