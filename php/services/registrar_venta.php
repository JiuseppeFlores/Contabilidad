<?php
include './functions.php';
$response = array();
date_default_timezone_set('America/La_Paz');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $pin = isset($_POST['pin']) ? $_POST['pin'] : '';
  $datos = login($pin);
  if($datos != -1){
    $SERVER = $datos['server'];
    $DB = $datos['base'];
    include './conexion.php';
    $fecha = date('Y-m-d');
    $tipo_cambio = isset($_POST['tipo_cambio']) ? $_POST['tipo_cambio'] : 0;
    $moneda = isset($_POST['moneda']) ? $_POST['moneda'] : 'Bolivianos';
    $glosa = isset($_POST['observacion']) ? $_POST['observacion'] : '---';
    $factura = isset($_POST['factura']) ? $_POST['factura'] : ''; // proforma o factura *
    $forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : '1';
    $monto = isset($_POST['monto']) ? $_POST['monto'] : 0;
    $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : 0;
    $rol_usuario = isset($_POST['rol_usuario']) ? $_POST['rol_usuario'] : '';
    $nom_usuario = isset($_POST['nombre_usuario']) ? $_POST['nombre_usuario'] : '';
    $cliente = isset($_POST['cliente']) ? $_POST['cliente'] : 'S/N';
    $tipo_pago = isset($_POST['tipo_pago']) ? $_POST['tipo_pago'] : '';
    // Peticion para obtener numero de comprobante
    $resultado = peticion_nro_comprobante_venta($fecha, $pin);
    if($resultado === false){
      $response['message'] = '[FAIL]: Fallido: PETICION Comprobante ';
    }else{
      $resultado = json_decode($resultado);
      if($resultado->success){
        $cantidad = $resultado->data;
        // Insertamos comrobante
        $glosa = $forma_pago == 'credito' ? $glosa. ' | Cliente: '.$cliente : $glosa;
        $id_comprobante = insertar_comprobante_venta($con, $cantidad, $fecha, $glosa, $tipo_cambio, $moneda);
        if($id_comprobante > 0){
          $res_asientos = insertar_asientos_venta($con, $monto, $id_comprobante, $forma_pago, $tipo_pago, $factura, $descuento, $rol_usuario, $nom_usuario);
          if($res_asientos != -1){
            $response['code'] = 1;
            $response['message'] = '[OK]: Comprobante y '.$res_asientos.' asientos insertados ';
          }else{
            $response['code'] = 0;
            $response['message'] = '[ERROR]: Error al Insertar asientos ';
          }
        }else{
          $response['code'] = 0;
          $response['message'] = '[FAIL]: Error: Insertar comprobante ';
        }
      }else{
        $response['code'] = 0;
        $response['message'] = '[FAIL]: Respuesta invalida  '.$resultado->message;
      }
    }
  }else{
    $response['code'] = 0;
    $response['message'] = '[FAIL]: Error en el login ';
  }
} else {
  $response['code'] = -1;
  $response['message'] = '[Bad Request 400]: Error en el tipo de petición (Requerido POST)';
}
echo json_encode($response);
?>

 <?php
// // Funciones 
function peticion_nro_comprobante_venta($fecha, $pin){
  try {
    $url = 'http://'.$_SERVER['SERVER_NAME'].'/contabilidad/php/services/obtener_num_comprobante.php?fecha='.urlencode($fecha).'&tipo=INGRESO&pin='.urlencode($pin);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $resultado = curl_exec($curl);
  } catch (\Throwable $th) {
    $resultado['success'] = false;
    $resultado['message'] = $th->getMessage();
  }
  return $resultado;
}

function insertar_comprobante_venta($con, $cantidad, $fecha, $glosa, $tipo_cambio, $moneda){
  $sql = "INSERT INTO tblComprobantes (numero, fecha, glosa, tipo, tipoCambio, moneda) VALUES (?, ?, ?, 'INGRESO', ?, ?);";
  $stmt = sqlsrv_query($con, $sql, array($cantidad, $fecha, $glosa, $tipo_cambio, $moneda));
  if($stmt){
    $stmt_id = sqlsrv_query($con, "SELECT IDENT_CURRENT('tblComprobantes') AS id;");
    if($stmt_id){
      $id = sqlsrv_fetch_array($stmt_id);
      return $id['id'];
    }else{
      return -1;
    }
  }else{
    return -1;
  }
}

function insertar_asientos_venta($con, $monto, $id_comprobante, $forma_pago, $tipo_pago, $factura, $descuento, $rol_usuario, $nom_usuario){
  include './cuentas_id.php';
  $Cuenta_ingreso = -1;
  if($tipo_pago != 'CREDITO'){//CONTADO
    $Cuenta_ingreso = $forma_pago == 'EFECTIVO' ? $CTA_CAJA : $CTA_BANCO;
  }else{
    if($rol_usuario == 'ADMIN' || $nom_usuario == 'ALMACEN'){
      $Cuenta_ingreso = $CTA_ALMACEN_CRED;
    }else if(($rol_usuario == 'VENDEDOR 1' || $rol_usuario == 'VENDEDOR 2') && str_starts_with($nom_usuario, 'MOVIL')){
      $Cuenta_ingreso = $CTA_MOVIL_CRED;
    }else if(($rol_usuario == 'PREVENTISTA 1' || $rol_usuario == 'PREVENTISTA 2') && str_starts_with($nom_usuario, 'PREV')){
      $Cuenta_ingreso = $CTA_PREVEN_CRED;
    }
  }
  $Cuenta_Asiento_3 = cuenta_vendedor($rol_usuario, $nom_usuario);
  if($descuento > 0){ // Con Descuento
    $total = $monto - $descuento;
    $iva = round(($monto - $monto*0.13), 2);
    $imp_transac = round($total*0.03, 2);
    $deb_fis = round($total*0.13, 2);
    $it_3 = round($total*0.03, 2);
    $desc_gc = round($descuento*0.87, 2);
    $sql = "INSERT INTO tblAsientos 
    (idComprobante, idCuenta, haber, debe, bco, cheque, referencia) VALUES 
    (?, ?, 0, ?, '', '', ''), 
    (?, ?, 0, ?, '', '', ''), 
    (?, ?, ?, 0, '', '', ''), 
    (?, ?, ?, 0, '', '', ''), 
    (?, ?, ?, 0, '', '', ''),
    (?, ?, 0, ?, '', '', '');";
    $params = array(
      $id_comprobante, $Cuenta_ingreso, $total, 
      $id_comprobante, $CTA_IT_GA, $imp_transac,
      $id_comprobante, $Cuenta_Asiento_3, $iva,
      $id_comprobante, $CTA_IVA_DEBITO, $deb_fis,
      $id_comprobante, $CTA_IT_X_PAGAR, $it_3,
      $id_comprobante, $CTA_DESCUENTO_GC, $desc_gc
    );
    $stmt = sqlsrv_query($con, $sql, $params);
    if($stmt){
      return 6;
    }else{
      return -1;
    }
  }else{ // Sin Descuento
    $iva = round(($monto - $monto*0.13), 2);
    $imp_transac = round($monto*0.03, 2);
    $deb_fis = round($monto*0.13, 2);
    $it_3 = round($monto*0.03, 2);
    $sql = "INSERT INTO tblAsientos 
    (idComprobante, idCuenta, haber, debe, bco, cheque, referencia) VALUES 
    (?, ?, 0, ?, '', '', ''), 
    (?, ?, 0, ?, '', '', ''), 
    (?, ?, ?, 0, '', '', ''), 
    (?, ?, ?, 0, '', '', ''), 
    (?, ?, ?, 0, '', '', '');";
    $params = array(
      $id_comprobante, $Cuenta_ingreso, $monto, 
      $id_comprobante, $CTA_IT_GA, $imp_transac,
      $id_comprobante, $Cuenta_Asiento_3, $iva,
      $id_comprobante, $CTA_IVA_DEBITO, $deb_fis,
      $id_comprobante, $CTA_IT_X_PAGAR, $it_3
    );
    $stmt = sqlsrv_query($con, $sql, $params);
    if($stmt){
      return 5;
    }else{
      return -1;
    }
  }
}

function str_starts_with($haystack, $needle) {
  return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
}

function cuenta_vendedor($rol_usuario, $nom_usuario){
  include './cuentas_id.php';
  if($nom_usuario == 'ALMACEN' && $rol_usuario == 'ADMIN'){
    $cta = $CTA_ALMACEN;
  }else if($rol_usuario == 'VENDEDOR 1' || $nom_usuario == 'MOVIL1'){
    $cta = $CTA_MOVIL1;
  }else if($rol_usuario == 'VENDEDOR 2' || $nom_usuario == 'MOVIL2'){
    $cta = $CTA_MOVIL2;
  }else if($rol_usuario == 'PREV1' || $nom_usuario == 'PREVENTISTA 1'){
    $cta = $CTA_PREV1;
  }else if($rol_usuario == 'PREV2' || $nom_usuario == 'PREVENTISTA 2'){
    $cta = $CTA_PREV2;
  }else{
    $cta = -1;
  }
  return $cta;
}


?>