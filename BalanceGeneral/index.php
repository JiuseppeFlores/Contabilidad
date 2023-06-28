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
    <title>Balance General</title>
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
                <h3>Balance General</h3>
            </div>
        </div>
        <div class="row mt-2 align-items-center">
            <div class="col-12">
                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                <div class="mb-3 mt-2 ms-2 me-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nivel" id="bg_grupo" value="G" disabled>
                        <label class="form-check-label" for="bg_grupo">Grupo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nivel" id="bg_rubro" value="R" disabled>
                        <label class="form-check-label" for="bg_rubro">Rubro</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nivel" id="bg_titulo" value="T" checked>
                        <label class="form-check-label" for="bg_titulo">Título</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nivel" id="bg_compuesta" value="C" disabled>
                        <label class="form-check-label" for="bg_compuesta">Compuesta</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nivel" id="bg_subcuenta" value="S" disabled>
                        <label class="form-check-label" for="bg_subcuenta">Sub Cuenta</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="" class="form-label text-muted"><b>Moneda:</b></label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="moneda" id="bg_bs" value="BOLIVIANOS" checked>
                        <label class="form-check-label" for="bg_bs">Bolivianos</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="moneda" id="bg_sus" value="DOLARES" disabled>
                        <label class="form-check-label" for="bg_sus">Dólares</label>
                    </div>
                    <!--<div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="moneda" id="bg_ufv" value="UFV" disabled>
                        <label class="form-check-label" for="bg_ufv">UFV</label>
                    </div>-->
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="moneda" id="bg_bm" value="BIMONETARIO" disabled>
                        <label class="form-check-label" for="bg_bm">Bimonetario</label>
                    </div>
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
