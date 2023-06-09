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
        <link rel="stylesheet" href="../css/ajustes.css">
        <script src="../js/bootstrap-table.min.js"></script>
    </head>
    <body>
        <?php
            include('../components/navbar.php');
            include('../components/toast.php');
            include('modal_registrar_comprobante.php');
            include('modal_registrar_factura.php');
            include('modal_cuentas.php');
            include('modal_anular_comprobante.php');
        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-6">
                    <h3>Comprobantes</h3>
                </div>
                <div class="col-6 text-end">
                    <!--<a class="btn btn-warning" data-bs-toggle="modal" href="#modal_lista_cuentas"><i class="bi bi-plus me-2"></i>Test</a>-->
                    <input type="checkbox" class="btn-check" id="btn_ver_comprobantes" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btn_ver_comprobantes" id="lbl_ver_comprobantes">Ver Anulados</label>
                    <button class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#modal_registrar_comprobante"><i class="bi bi-plus me-2"></i>Adicionar</button>
                </div>
            </div>
            <div class="row mt-2">
                <?php
                    include('../Components/pagination.php');
                ?>
            </div>
            <div class="row mt-2">

            </div>
            <div class="row mt-2" id="content" style="height:100vh">
                <div class="table-responsive">
                    <table class="table table-hover align-middle"
                            id="tbl_comprobantes">

                    </table>
                </div>
            </div>
        </div>
    </body>
    <?php
        //include('../Components/footer.php');
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
