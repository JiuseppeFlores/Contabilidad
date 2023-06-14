<?php
    include('php/functions.php');
    isSessionActive();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv= 0  content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contabilidad</title>
    <!-- BOOTSTRAP -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="js/jquery.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button
        class="navbar-toggler"
        type="button"
        data-mdb-toggle="collapse"
        data-mdb-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
        >
        <i class="fas fa-bars"></i>
        </button>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
                CONTABILIDAD
            </a>
        <!-- Left links -->
        </div>
        <div class="d-flex" role="search">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="nav_cerrar_sesion">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
        <!-- Collapsible wrapper -->
    </div>
    <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cuentas</h5>
                        <p class="card-text">Gestione las cuentas contables de su negocio.</p>
                        <a href="cuentas/index.php" class="btn btn-primary">Ir a cuentas</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Comprobantes</h5>
                        <p class="card-text">Registrar comprobantes de las transacciones de su negocio.</p>
                        <a href="comprobantes/index.php" class="btn btn-primary">Registrar comprobantes</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Libro Mayor</h5>
                        <p class="card-text">Documento que incluye los movimientos de cada una de las cuentas de la empresa por separado.</p>
                        <button onclick="console.log('asd')" class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
