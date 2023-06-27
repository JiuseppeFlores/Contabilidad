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
    <title>Balance Sumas y Saldos</title>
    <!-- BOOTSTRAP -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-table.min.css">
    <script src="../js/bootstrap-table.min.js"></script>
</head>
<body>
    <?php
        include('../Components/navbar.php');
        include('../Components/toast.php');
    ?>
    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-6">
                <h3>Balance de Sumas y Saldos</h3>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" disabled>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Varias Cuentas</label>
                </div>
            </div>
        </div>
        <div class="row mt-2 align-items-center">
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" name="descripcion" id="lm_descripcion" class="form-control form-control-sm" require readonly value=""/>
                    <input type="hidden" name="codigo" id="lm_codigo"/>
                    <label>Cuenta</label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="date" name="fecha_inicial" id="lm_fecha_inicial" class="form-control form-control-sm" require autocomplete="off"/>
                    <label for="lm_fecha_inicial">Fecha Inicial</label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="date" name="fecha_final" id="lm_fecha_final" class="form-control form-control-sm" require autocomplete="off"/>
                    <label for="lm_fecha_final">Fecha Final</label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <button class="btn btn-primary" id="btn_ver_registros"><i class="bi bi-arrow-clockwise me-2"></i>Ver Registros</button>
                <button class="btn btn-success" id="btn_generar_reporte"><i class="bi bi-table me-2"></i>Generar Reporte</button>
            </div>
        </div>
        <div class="row">
            <div class="card p-2" style="width:100% !important;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover caption-top" id="tbl_registros">
                        
                    </table>
                </div>  
            </div>
        </div>
    </div>
    <?php
        include('../Components/footer.php');
    ?>
</body>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/environment.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</html>
