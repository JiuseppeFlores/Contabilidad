<?php
    include('../php/functions.php');
    isSessionActive('../');
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
        <!-- Tabulator -->
        <link href="../js/tabulator/dist/css/tabulator_semanticui.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            include('../components/navbar.php');
            include('../components/toast.php');
            //include('modal_registrar_comprobante.php');
            include('modal_cuentas.php');
        ?>
        <div class="container-fluid mt-4 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h3>Registrar Comprobante</h3>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_lista_cuentas"><i class="bi bi-plus me-2"></i>Test</button>
                    <button class="btn btn-success" onclick="adicionar_comprobante()"><i class="bi bi-plus me-2"></i>Adicionar</button>
                </div>
            </div>
            <form id="form_registro_comprobante">
                <div class="row mt-2">
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="text" name="nro_comprobante" id="nro_comprobante" class="form-control form-control-sm" minlength="3" maxlength="100" required autocomplete="off" readonly/>
                            <label for="nro_comprobante">Nro de Comprobante</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <select class="form-select my-select form-select-sm" name="tipo" required id="comprobante_tipo">
                                <option value="" disabled selected> - Tipo - </option>
                                <option value="INGRESO">INGRESO</option>
                                <option value="EGRESO">EGRESO</option>
                                <option value="TRASPASO">TRASPASO</option>
                            </select>
                            <label for="comprobante_tipo">Tipo de Comprobante</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="date" class="form-control form-control-sm" name="fecha" id="comprobante_fecha">
                            <label for="comprobante_fecha">Fecha</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="decimal" name="tipo_cambio" id="comprobante_tipo_cambio" class="form-control form-control-sm" minlength="1" maxlength="100" required autocomplete="off"/>
                            <label for="comprobante_tipo_cambio">Tipo de Cambio</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <select class="form-control form-select form-select-sm" name="moneda" required id="comprobante_moneda">
                                <option value="BS" selected>Bs.</option>
                                <option value="SUS">SU$</option>
                            </select>
                            <label for="comprobante_moneda">Moneda</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="text" name="detalle" id="comprobante_detalle" class="form-control form-control-sm" minlength="3" maxlength="100" required autocomplete="off"/>
                            <label for="comprobante_detalle">Detalle</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="number" name="nit_ci" id="comprobante_nit_ci" class="form-control form-control-sm" minlength="3" maxlength="100" autocomplete="off"/>
                            <label for="comprobante_nit_ci">NIT/CI</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-floating">
                            <input type="number" name="nro_recibo" id="comprobante_nro_recibo" class="form-control form-control-sm" minlength="3" maxlength="100" autocomplete="off"/>
                            <label for="comprobante_nro_recibo">Nro. de Recibo</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input class="form-control form-control-sm" id="comprobante_glosa" name="glosa" autocomplete="off"/>
                            <label for="comprobante_glosa">Glosa</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="d-flex flex-row">
                        <h5 class="me-2">Asientos</h5>
                        <!--<button class="btn btn-sm btn-success me-2" type="button" id="btn_adicionar_asiento"><i class="bi bi-plus"></i></button>
                        <button class="btn btn-sm btn-danger" type="button" id="btn_remover_asiento"><i class="bi bi-dash"></i></button>-->
                        <a class="edit" href="javascript:void(0)" title="Editar" id="btn_adicionar_asiento"><i class="bi bi-plus-circle-fill text-success fs-5"></i></a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-sm"
                                id="tbl_asientos">

                        </table>
                    </div>
                </div>
                <div class="row mt-2">
                    <button class="btn btn-sm btn-success me-2" type="submit"><i class="bi bi-plus me-2"></i>Registrar</button>
                </div>
            </form>
        </div>
    </body>
    <?php
        include('../Components/footer.php');
    ?>
    <script type="text/javascript" src="../js/environment.js"></script>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    
    <script type="text/javascript" src="js/Asiento.js"></script>
    <script type="text/javascript" src="js/components.js"></script>

    <script type="text/javascript" src="../js/tabulator/dist/js/tabulator.min.js"></script>
    <script type="text/javascript" src="js/registrar.js"></script>
</html>
