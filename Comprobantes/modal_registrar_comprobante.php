<form id="form_registro_comprobante">
    <div class="modal fade" id="modal_registrar_comprobante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width:100% !important;margin:20px;max-width:100% !important;">
            <div class="modal-content" style="width:95vw !important;margin:20px;">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title">REGISTRO DE COMPROBANTES</h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control form-control-sm" minlength="3" maxlength="100" required autocomplete="off" readonly/>
                                    <label for="nro_comprobante">Nro de Comprobante</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-select my-select" name="tipo" required id="comprobante_tipo" onchange="obtenerNroComprobante()">
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
                                    <input type="date" class="form-control" name="fecha" id="comprobante_fecha" onchange="obtenerNroComprobante()">
                                    <label for="comprobante_fecha">Fecha</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="decimal" name="tipo_cambio" id="comprobante_tipo_cambio" class="form-control" minlength="1" maxlength="100" required autocomplete="off" value="6.96"/>
                                    <label for="comprobante_tipo_cambio">Tipo de Cambio</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <select class="form-control form-select" name="moneda" required id="comprobante_moneda">
                                        <option value="" disabled> - Moneda - </option>
                                        <option value="BS" selected>Bs.</option>
                                        <option value="SUS">SU$</option>
                                    </select>
                                    <label for="comprobante_moneda">Moneda</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="text" name="detalle" id="comprobante_detalle" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="comprobante_detalle">Corresponde</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="number" name="nit_ci" id="comprobante_nit_ci" class="form-control" minlength="3" maxlength="100" autocomplete="off"/>
                                    <label for="comprobante_nit_ci">NIT/CI</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating">
                                    <input type="number" name="nro_recibo" id="comprobante_nro_recibo" class="form-control" minlength="3" maxlength="100" autocomplete="off"/>
                                    <label for="comprobante_nro_recibo">Nro. de Recibo</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input class="form-control" id="comprobante_glosa" name="glosa" autocomplete="off"/>
                                    <label for="comprobante_glosa">Glosa</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm text-center" onclick="adicionar_asiento()"><i class="bi bi-plus me-2"></i>Añadir Asiento</button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm text-center" onclick="removerAsiento()"><i class="bi bi-dash me-2"></i>Remover Asiento</button>
                            </div>
                            <div class="col-md-5">
                                <input type="file" class="form-control" id="pdfFile" accept=".pdf" name="pdfFile"/>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="table-responsive" style="overflow-y: scroll; height: 350px;">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="sticky-top">
                                        <tr class="table-info text-center">
                                            <th scope="col">CÓDIGO</th>
                                            <th scope="col">CUENTA</th>
                                            <th scope="col">REFERENCIA</th>
                                            <!--<th scope="col">C.C.</th>-->
                                            <th scope="col">DEBE (Bs)</th>
                                            <th scope="col">HABER (Bs)</th>
                                            <th scope="col">DEBE ($us)</th>
                                            <th scope="col">HABER ($us)</th>
                                            <th scope="col">BCO.</th>
                                            <th scope="col">CHEQUE</th>
                                            <th scope="col"> - </th>
                                        </tr>
                                    </thead>
                                    <tbody id="asientos">

                                    </tbody>
                                    <tfoot class="sticky-bottom bg-light">
                                        <tr>
                                            <th scope="col" colspan="3" style="text-align:center;">TOTALES: </th>
                                            <th class="text-center" scope="col" id="total_debe"></th>
                                            <th class="text-center" scope="col" id="total_haber"></th>
                                            <th class="text-center" scope="col" id="total_debe_dolar"></th>
                                            <th class="text-center" scope="col" id="total_haber_dolar"></th>
                                            <th scope="col" colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="../Cuentas/" class="btn btn-sm btn-primary" target="_blank"><i class="bi bi-list-columns-reverse me-2"></i>Ver Cuentas</a>
                    <a href="../LibroMayor/" class="btn btn-sm btn-info" target="_blank"><i class="bi bi-list-columns-reverse me-2"></i>Ver Libro Mayor</a>
                    <button type="button" onclick="limpiarModal()" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check me-2"></i>REGISTRAR</button>
                </div>
            </div>
        </div>
    </div>
</form>