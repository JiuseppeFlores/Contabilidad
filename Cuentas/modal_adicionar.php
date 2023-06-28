<form id="form_adicionar_cuenta">
    <div class="modal fade" id="modal_adicionar" tabindex="-1" aria-labelledby="modal_adicionar_titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="modal_adicionar_titulo">ADICIONAR CUENTA</h5>
                </div>
                <div class="modal-body" tabindex="-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="ca_descripcion_span"><i class="bi-list-columns-reverse text-secondary"></i></span>
                                    <input type="text" class="form-control" id="ca_descripcion" aria-describedby="ca_descripcion_span" placeholder="Seleccione cuenta" readonly data-bs-target="#modal_lista_cuentas" data-bs-toggle="modal" required>
                                    <input type="hidden" id="ca_nivel" name="nivel"/>
                                    <input type="hidden" id="ca_grupo" name="grupo"/>
                                    <input type="hidden" id="ca_rubro" name="rubro"/>
                                    <input type="hidden" id="ca_titulo" name="titulo"/>
                                    <input type="hidden" id="ca_compuesta" name="compuesta"/>
                                    <input type="hidden" id="ca_subcuenta" name="subcuenta"/>
                                </div>
                            </div>
                            <label for="" class="form-label text-muted"><b>Cuenta Contable:</b></label>
                            <div class="col-12">
                                <label for="ca_codigo" class="form-label">Código Cuenta</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="ca_codigo_base"></span>
                                    <input type="text" class="form-control" name="codigo_cuenta" id="ca_codigo_cuenta" aria-describedby="ca_codigo_base" minlength="1" maxlength="2" required autocomplete="off" onblur="actualizarCodigo()"/>
                                    <input type="hidden" id="ca_codigo" name="codigo"/>
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
                                    <input class="form-check-input" type="checkbox" value="" id="ca_movimiento" name="movimiento" readonly>
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