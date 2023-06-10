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
    </head>
    <body>
        <?php
            include('../components/navbar.php');
            include('../components/toast.php');
            include('modal_registrar_comprobante.php');
        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-6">
                    <h3>Comprobantes</h3>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-success" onclick="adicionar_comprobante()"><i class="bi bi-plus me-2"></i>Adicionar</button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered align-middle">
                        <thead>
                            <tr class="table-primary text-center">
                                <th scope="col">#</th>
                                <th scope="col">N&Uacute;MERO</th>
                                <th scope="col">TIPO</th>
                                <th scope="col">FECHA</th>
                                <th scope="col">MONEDA</th>
                                <th scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider" id="lista_comprobantes">
                            
                        </tbody>
                    </table>
                </div>
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
        <script type="text/javascript" src="../js/environment.js"></script>
        <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="../js/select2.min.js"></script>
        <script type="text/javascript" src="../js/components.js"></script>
        <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript" src="js/app.js"></script>
        <script type="text/javascript" src="js/Asiento.js"></script>
        <script type="text/javascript" src="js/components.js"></script>
    </body>
</html>
