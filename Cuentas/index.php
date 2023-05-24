<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv= 0  content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuentas</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="../css/mdb.min.css" rel="stylesheet" />
    <script src="../js/jquery.min.js"></script>
</head>
<body>
    <?php
        include('../components/navbar.php');
        include('modal_adicionar.php');
        include('modal_actualizar.php');
        include('modal_eliminar.php');
        include('alertas.php');
    ?>
    <div class="container mt-4">
        <div class="row" id="contenedor_mensaje">
            <div class="alert alert-dismissible fade hide" role="alert" id="alerta">
                <strong id="titulo_mensaje"></strong> <label id="cuerpo_mensaje"></label>
                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h3>Cuentas</h3>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" data-mdb-toggle="modal" data-mdb-target="#modal_adicionar"><i class="fas fa-plus me-2"></i>Adicionar</button>
            </div>
        </div>
        <div class="row mt-2">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr class="table-primary text-center">
                        <th scope="col">#</th>
                        <th scope="col">C&Oacute;DIGO</th>
                        <th scope="col">CUENTA</th>
                        <th scope="col">GRUPO</th>
                        <th scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="cuentas">
                    
                </tbody>
            </table>
        </div>
        <div class="row mt-2">
            <?php
                include('../Components/pagination.php');
            ?>
        </div>
    </div>

        <!-- Datos ajax Final -->
    <div class="content" align="center"> 
        <center><p>&copy; STIS <?php echo date('Y');?></p></center>
    </div>
</body>
    <script type="text/javascript" src="../js/mdb.min.js"></script>
    <script type="text/javascript" src="../js/components.js"></script>
    <script type="text/javascript" src="js/environment.js"></script>
    <script type="text/javascript" src="js/components.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</html>
