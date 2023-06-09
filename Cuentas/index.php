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
    <title>Cuentas</title>
    <!-- BOOTSTRAP -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-table.min.css">
    <script src="../js/bootstrap-table.min.js"></script>
</head>
<body>
    <?php
        include('../components/navbar.php');
        include('modal_adicionar.php');
        include('modal_actualizar.php');
        include('modal_eliminar.php');
        include('../components/toast.php');
        include('../Components/modal_cuentas.php');
    ?>
    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-6">
                <h3>Plan de Cuentas</h3>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_adicionar"><i class="bi bi-plus me-2"></i>Adicionar</button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="tbl_cuentas">
                    
                </table>
            </div>
        </div>
        
        <div class="row mt-2">
            <?php
                include('../Components/pagination.php');
            ?>
        </div>
    </div>
</body>
    <?php
        include('../Components/footer.php');
    ?>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/environment.js"></script>
    <script type="text/javascript" src="js/components.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</html>
