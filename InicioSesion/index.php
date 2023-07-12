<?php
  include("../php/functions.php");
  if(isSessionStarted()){
    header('Location: ../index.php');
  };
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Contabilidad</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- MDB -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <script src="../js/jquery.min.js"></script>
  <style>
    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }
    .h-custom {
      height: calc(100% - 72px);
    }
    @media (max-width: 450px) {
      .h-custom {
        height: 100%;
      }
    }
  </style>
</head>

<body>
  <?php
    include('../components/toast.php');
  ?>
  <!-- Start your project here-->
  <section class="vh-100">
    <div class="container-fluid h-custom">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp" class="img-fluid"
            alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
          <form id="form_iniciar_sesion">
            <!-- MODOS DE INICIO DE SESION -->
            <!--<div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
              <p class="lead fw-normal mb-0 me-3">Sign in with</p>
              <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-facebook-f"></i>
              </button>

              <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-twitter"></i>
              </button>

              <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-linkedin-in"></i>
              </button>
            </div>

            <div class="divider d-flex align-items-center my-4">
              <p class="text-center fw-bold mx-3 mb-0">Or</p>
            </div>-->
            <div class="card">
                <div class="card-header p-3 text-center text-light" style="background:#3B71CA;">
                    <h3 class="h3 m-0">CONTABILIDAD</h3>
                </div>
                <div class="card-body">
                    <!-- PIN -->
                    <div class="form-floating mb-4">
                      <input type="text" id="is_pin" name="pin" class="form-control form-control-lg"
                          placeholder="Ingrese su PIN" />
                      <label for="is_pin">PIN</label>
                    </div>

                    <!-- Usuario -->
                    <div class="form-floating mb-4">
                      <input type="text" id="is_usuario" name="usuario" class="form-control form-control-lg"
                          placeholder="Ingrese su usuario" />
                      <label for="is_usuario">Usuario</label>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-floating mb-3">
                      <input type="password" id="is_contrasenia" name="contrasenia" class="form-control form-control-lg"
                          placeholder="Ingrese su contrasenia" />
                      <label class="form-label" for="is_contrasenia">Contraseña</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Checkbox -->
                        <!--<div class="form-check mb-0">
                            <input class="form-check-input me-2" type="checkbox" value="" id="is_recordarme" />
                            <label class="form-check-label" for="is_recordarme"> Recordarme </label>
                        </div>-->
                        <!--<a href="#!" class="text-body">Forgot password?</a>-->
                    </div>

                    <div class="text-center text-lg-start mt-2 pt-2">
                        <button type="submit" class="btn btn-success btn-lg" style="width:100%;"><i class="bi-box-arrow-in-right me-2"></i>INGRESAR</button>
                        <!--<p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!"
                            class="link-danger">Register</a></p>-->
                    </div>  
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5" style="background:#3B71CA;">
      <!-- Copyright -->
      <div class="text-white mb-3 mb-md-0">
        CONTABILIDAD &copy; STIS BOLIVIA <?php echo date('Y');?>
      </div>
      <!-- Copyright -->

      <!-- Right -->
      <!--<div>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-google"></i>
        </a>
        <a href="#!" class="text-white">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>-->
      <!-- Right -->
    </div>
  </section>
  <!-- End your project here-->

  <!-- MDB -->
  <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="../js/components.js"></script>
  <!-- Custom scripts -->
  <script type="text/javascript" src="js/app.js"></script>
</body>
</html>