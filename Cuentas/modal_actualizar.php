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
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="ce_descripcion_span"><i class="bi-list-columns-reverse text-secondary"></i></span>
                                    <input type="text" class="form-control" id="ce_descripcion" aria-describedby="ce_descripcion_span" placeholder="Seleccione cuenta" readonly data-bs-target="#modal_lista_cuentas" data-bs-toggle="modal" data-bs-tipo="ce" required>
                                    <input type="hidden" id="ce_nivel" name="nivel"/>
                                    <input type="hidden" id="ce_grupo" name="grupo"/>
                                    <input type="hidden" id="ce_rubro" name="rubro"/>
                                    <input type="hidden" id="ce_titulo" name="titulo"/>
                                    <input type="hidden" id="ce_compuesta" name="compuesta"/>
                                    <input type="hidden" id="ce_subcuenta" name="subcuenta"/>
                                </div>
                            </div>
                            <label for="" class="form-label text-muted"><b>Cuenta Contable:</b></label>
                            <div class="col-12">
                                <label for="ce_codigo" class="form-label">Código Cuenta</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="ce_codigo_base"></span>
                                    <input type="text" class="form-control" name="codigo_cuenta" id="ce_codigo_cuenta" aria-describedby="ce_codigo_base" minlength="1" maxlength="3" required autocomplete="off" onblur="actualizarCodigo('ce')"/>
                                    <input type="hidden" id="ce_codigo" name="codigo"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input id="ce_nombre_cuenta" type="text" name="descripcion" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="ce_descripcion">Nombre</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="ce_movimiento" name="movimiento" readonly>
                                    <label class="form-check-label" for="ce_movimiento">
                                        Movimiento
                                    </label>
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