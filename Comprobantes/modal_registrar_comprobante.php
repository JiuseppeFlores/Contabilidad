<form id="form_registro_comprobante">
    <div class="modal fade" id="modal_registrar_comprobante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off" readonly/>
                                    <label for="nro_comprobante">Nro de Comprobante</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-select my-select" name="tipo" required id="comprobante_tipo">
                                        <option value="" disabled selected> - Tipo - </option>
                                        <option value="INGRESO">INGRESO</option>
                                        <option value="EGRESO">EGRESO</option>
                                        <option value="TRASPASO">TRASPASO</option>
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
                                    <input type="decimal" name="tipo_cambio" id="comprobante_tipo_cambio" class="form-control" minlength="1" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_tipo_cambio">Tipo de Cambio</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-control form-select" name="moneda" required id="comprobante_moneda">
                                        <option value="" disabled selected> - Moneda - </option>
                                        <option value="BS">Bs.</option>
                                        <option value="SUS">SU$</option>
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
                                        <option value="1">PRUEBA</option>
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
                                    <input class="form-control" id="comprobante_glosa" name="glosa"/>
                                    <label for="comprobante_glosa">Glosa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <button type="button" class="btn btn-primary text-center" onclick="adicionar_asiento()"><i class="bi bi-plus me-2"></i>Añadir Asiento</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered align-middle">
                                    <thead>
                                        <tr class="table-info text-center">
                                            <th scope="col">CÓDIGO</th>
                                            <th scope="col">CUENTA</th>
                                            <th scope="col">REFERENCIA</th>
                                            <th scope="col">C.C.</th>
                                            <th scope="col">DEBE</th>
                                            <th scope="col">HABER</th>
                                            <th scope="col">BCO.</th>
                                            <th scope="col">CHEQUE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="asientos">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th class="text-center" scope="col" id="total_debe"></th>
                                            <th class="text-center" scope="col" id="total_haber"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check me-2"></i>REGISTRAR</button>
                </div>
            </div>
        </div>
    </div>
</form>