<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv= 0  content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Cuentas</title>
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
        include('alertas.php');
    ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-6">
                <h3>Cuentas</h3>
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success" data-mdb-toggle="modal" data-mdb-target="#modal_adicionar"><i class="fas fa-plus me-2"></i>Adicionar</button>
            </div>
        </div>
        <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">C&Oacute;DIGO</th>
                    <th scope="col">CUENTA</th>
                    <th scope="col">GRUPO</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
                </tr>
                <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
                <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

        <!-- Datos ajax Final -->
    <div class="content" align="center"> 
        <center><p>&copy; STIS <?php echo date('Y');?></p></center>
    </div>
</body>
    <script type="text/javascript" src="../js/mdb.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</html>
