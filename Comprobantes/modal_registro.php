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
                                    <option value="" disabled selected> - Tipo - </option>
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
                                    <label class="form-label" for="descripcion">T/C</label>
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
                                    <option value="" disabled selected> - Moneda - </option>
                                    <option value="ACTIVO">Bs.</option>
                                    <option value="PASIVO">SU$</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2">
                                <div class="form-outline">
                                    <input type="text" name="tipo_cambio" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label class="form-label" for="descripcion">Cancelado a</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-outline">
                                    <input type="number" name="tipo_cambio" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label class="form-label" for="descripcion">NIT/CI</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-outline">
                                    <input type="number" name="tipo_cambio" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label class="form-label" for="descripcion">Nro. de Recibo</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-outline">
                                    <input type="number" name="tipo_cambio" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label class="form-label" for="descripcion">Nro de Comprobante</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-outline">
                                    <textarea class="form-control" id="textAreaExample" rows="4"></textarea>
                                    <label class="form-label" for="textAreaExample">Glosa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">CÓDIGO</th>
                                        <th scope="col">CUENTA</th>
                                        <th scope="col">REFERENCIA</th>
                                        <th scope="col">C.C.</th>
                                        <th scope="col">DEBE (Bs.)</th>
                                        <th scope="col">HABER (Bs.)</th>
                                        <th scope="col">DEBE (US$)</th>
                                        <th scope="col">HABER (US$)</th>
                                        <th scope="col">BCO.</th>
                                        <th scope="col">N° CHEQUE</th>
                                        <th scope="col">IVA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                        <td>Mark</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Jacob</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td colspan="2">Larry the Bird</td>
                                        <td>@twitter</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                        <td>Jacob</td>
                                    </tr>
                                </tbody>
                            </table>
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