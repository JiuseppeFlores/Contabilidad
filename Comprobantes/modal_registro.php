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
                                <div class="form-floating">
                                    <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off" disabled value="1"/>
                                    <label for="nro_comprobante">Nro de Comprobante</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-select" name="tipo" required id="comprobante_tipo"  aria-label="Tipo de Comprobante">
                                        <option value="" disabled selected> - Tipo - </option>
                                        <option value="ACTIVO">INGRESO</option>
                                        <option value="PASIVO">EGRESO</option>
                                        <option value="PATRIMONIO">TRASPASO</option>
                                    </select>
                                    <label for="comprobante_tipo">Tipo de Comprobante</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="fecha" id="comprobante_fecha">
                                    <label for="comprobante_fecha">Fecha</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="number" name="tipo_cambio" id="comprobante_tipo_cambio" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_tipo_cambio">Tipo de Cambio</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-control form-select" name="moneda" required id="comprobante_moneda">
                                        <option value="" disabled selected> - Moneda - </option>
                                        <option value="ACTIVO">Bs.</option>
                                        <option value="PASIVO">SU$</option>
                                    </select>
                                    <label for="comprobante_moneda">Moneda</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-control form-select" name="proyecto" required id="comprobante_proyecto">
                                        <option value="" disabled selected> - Proyecto - </option>
                                    </select>
                                    <label for="comprobante_proyecto">Proyecto</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="text" name="cancelado" id="comprobante_cancelado" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_cancelado">Cancelado a</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="number" name="nit_ci" id="comprobante_nit_ci" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_nit_ci">NIT/CI</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="number" name="nro_recibo" id="comprobante_nro_recibo" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_nro_recibo">Nro. de Recibo</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="comprobante_glosa" name="glosa" rows="2"></textarea>
                                    <label for="comprobante_glosa">Glosa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row m-2">
                            <table class="table table-hover table-bordered border-primary">
                                <thead>
                                    <tr>
                                        <th scope="col">CÓDIGO</th>
                                        <th scope="col">CUENTA</th>
                                        <th scope="col">REFERENCIA</th>
                                        <th scope="col">C.C.</th>
                                        <th scope="col">DEBE</th>
                                        <th scope="col">HABER</th>
                                        <th scope="col">BCO.</th>
                                        <th scope="col">N° CHEQUE</th>
                                    </tr>
                                </thead>
                                <tbody id="asientos">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11">
                                            <button type="button" class="btn btn-success" onclick="adicionar_asiento()"><i class="bi bi-plus me-2"></i></i>ADICIONAR</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                </div>
            </div>
        </div>
    </div>
</form>