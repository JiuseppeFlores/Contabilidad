<form id="form_registro_comprobante">
    <div class="modal fade" id="modal_registro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">REGISTRO DE COMPROBANTES</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-2">
                                <select class="form-control form-control-lg form-select text-muted" name="tipo" required id="comprobante_tipo">
                                    <option value="" disabled selected> - Seleccione tipo - </option>
                                    <option value="ACTIVO">INGRESO</option>
                                    <option value="PASIVO">EGRESO</option>
                                    <option value="PATRIMONIO">TRASPASO</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <div class="form-outline datepicker">
                                    <input type="text" class="form-control form-icon-trailing" id="comprobante_fecha">
                                    <label for="comprobante_fecha" class="form-label">Fecha</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-outline">
                                    <input type="number" name="tipo_cambio" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label class="form-label" for="descripcion">Tipo de Cambio</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <select class="form-control form-control-lg form-select text-muted" name="tipo" required id="comprobante_tipo">
                                    <option value="" disabled selected> - Seleccione tipo - </option>
                                    <option value="ACTIVO">INGRESO</option>
                                    <option value="PASIVO">EGRESO</option>
                                    <option value="PATRIMONIO">TRASPASO</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <select class="form-control form-control-lg form-select text-muted" name="tipo" required id="comprobante_tipo">
                                    <option value="" disabled selected> - Seleccione moneda - </option>
                                    <option value="ACTIVO">Bolivianos</option>
                                    <option value="PASIVO">EGRESO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal"><i class="fas fa-xmark me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus me-2"></i>ADICIONAR</button>
                </div>
            </div>
        </div>
    </div>
</form>