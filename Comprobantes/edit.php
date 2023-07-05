<?php
  include('../php/functions.php');
  isSessionActive('../');
  include_once('../conexion.php');
  if(isset($_GET['id'])){

  }else{
    header('Location: ./');
  }
?>
<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv= 0  content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comprobantes</title>
        <!-- BOOTSTRAP -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href="../css/select2.min.css" rel="stylesheet" />
        <script src="../js/jquery.min.js"></script>
        <link rel="stylesheet" href="../css/bootstrap-table.min.css">
        <script src="../js/bootstrap-table.min.js"></script>
    </head>
    <body>
        <?php
            include('../components/navbar.php');
            include('../components/toast.php');
            include('modal_cuentas.php');
        ?>
        <div class="container mt-4">
          <div class="row">
              <div class="col-6">
                  <h3>Editar Comprobante</h3>
              </div>
          </div>
          <form id="form_registro_comprobante">
            <div class="" id="modal_registrar_comprobante">
              <div class="modal-dialog modal-dialog-centered" style="width:100% !important;margin:20px;max-width:100% !important;">
                <div class="modal-content" style="width:95vw !important;margin:20px;">
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
                            <label for="comprobante_detalle">Detalle</label>
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
                        <div class="col-md-5">
                          <input type="file" class="form-control" id="pdfFile" accept=".pdf" name="pdfFile"/>
                        </div>
                        <div class="col-md-5">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered align-middle">
                            <thead>
                              <tr class="table-info text-center">
                                <th scope="col">CÓDIGO</th>
                                <th scope="col">CUENTA</th>
                                <th scope="col">REFERENCIA</th>
                                <th scope="col">DEBE (Bs)</th>
                                <th scope="col">HABER (Bs)</th>
                                <th scope="col">DEBE ($us)</th>
                                <th scope="col">HABER ($us)</th>
                                <th scope="col">BCO.</th>
                                <th scope="col">CHEQUE</th>
                              </tr>
                            </thead>
                            <tbody id="asientos">
                              <tr>
                                <td>
                                  <div class="form-outline">
                                    <input type="text" id="codigo-1" name="referencia[]" class="form-control" placeholder="Seleccione Cuenta" autocomplete="off">
                                  </div>
                                  <input type="hidden" name="cuenta[]" id="id-cuenta-1">
                                </td>
                                <td>
                                  <span id="sp-1"></span>
                                </td>
                                <td>
                                  <div class="form-outline">
                                    <input type="text" id="referencia-1" name="referencia[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-outline">
                                    <input type="decimal" id="debe-1" name="debe[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-outline">
                                    <input type="decimal" id="haber-1" name="haber[]" class="form-control">
                                  </div>
                                </td>
                                <td id="debe-1-s">0</td>
                                <td id="haber-1-s">0</td>
                                <td>
                                  <div class="form-outline">
                                    <input type="text" id="banco-1" name="banco[]" class="form-control">
                                  </div>
                                </td>
                                <td>
                                  <div class="form-outline">
                                    <input type="text" id="cheque-1" name="cheque[]" class="form-control">
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col" colspan="3"></th>
                                    <th class="text-center" scope="col" id="total_debe"></th>
                                    <th class="text-center" scope="col" id="total_haber"></th>
                                    <th scope="col" id="total_debe_s"></th>
                                    <th scope="col" id="total_haber_s"></th>
                                    <th scope="col" colspan="3"></th>
                                </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check me-2"></i>ACTUALIZAR</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row mt-2">
            <?php
                include('../Components/pagination.php');
            ?>
          </div>
        </div>
    </body>
    <?php
        // include('../Components/footer.php');
    ?>
    <script type="text/javascript" src="../js/environment.js"></script>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/select2.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/Asiento.js"></script>
    <script type="text/javascript" src="js/components.js"></script>
</html>
