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
    <script src="../js/jquery.min.js"></script>
</head>
<body>
    <?php
        include('../Components/navbar.php');
        include('../Components/toast.php');
    ?>
    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-6">
                <h3>Libro Mayor</h3>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Varias Cuentas</label>
                </div>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_adicionar"><i class="bi bi-plus me-2"></i>Adicionar</button>
            </div>
        </div>
        <div class="row">
            <div class="card" style="width:100% !important;">
                
            </div>
        </div>
        <div class="row mt-2">

        </div>
        <div class="row mt-2">
            <?php
                include('../Components/pagination.php');
            ?>
        </div>
    </div>
    <?php
        include('../Components/footer.php');
    ?>
</body>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/environment.js"></script>
</html>
