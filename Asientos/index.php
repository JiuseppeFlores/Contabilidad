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
        <title>Asientos</title>
        <!-- BOOTSTRAP -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href="../css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/bootstrap.icons.css">
        <script src="../js/jquery.min.js"></script>
        <link rel="stylesheet" href="../css/bootstrap-table.min.css">
        <script src="../js/bootstrap-table.min.js"></script>
    </head>
    <body>
        <?php
            include('../components/navbar.php');
            include('../components/toast.php');
            include('modal_subir_excel.php');
        ?>
        <div class="container mt-4" style="height: calc(100vh - 130px);">
            <div class="row">
                <div class="col-6">
                  <h3>Asientos Contables</h3>
                </div>
                <div class="col-6 text-end">
                    <a class="btn btn-success" data-bs-toggle="modal" href="#modal_subir_excel"><i class="bi bi-file-earmark-arrow-up-fill"></i> Subir excel - Emparejamiento</a>
                    
                </div>
            </div>
            <div class="row mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle"
                            id="tbl_asientos">
                            
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
    <script type="text/javascript" src="../js/environment.js"></script>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../js/select2.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</html>
