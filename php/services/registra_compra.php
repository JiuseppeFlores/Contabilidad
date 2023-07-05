<?php
include_once '../../conexion_.php';

$response = array();
date_default_timezone_set('America/La_Paz');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validaciones valores 
  $tipo_cambio = isset($_POST['tipo_cambio']) ? $_POST['tipo_cambio'] : 6.96;
  $moneda = isset($_POST['moneda']) ? $_POST['moneda'] : 'BS';
  $monto = isset($_POST['monto']) ? $_POST['monto'] : 0;
  $glosa = isset($_POST['glosa']) ? $_POST['glosa'] : '';
  $fact_reten = isset($_POST['factura_ret']) ? $_POST['factura_ret'] : 'no';
  $forma_pago = isset($_POST['forma_pago']) ? $_POST['forma_pago'] : 'caja';
  $cod_inventario = isset($_POST['cod_inventario']) ? $_POST['cod_inventario'] : '';
  $fecha = date('Y-m-d');

  // hacemos la peticion para obtener nro comprobante
  $resultado = peticion_nro_comprobante($fecha);
  if($resultado === false){
    $response['message'] = '[FAIL]: Fallido: PETICION Comprobante '.curl_error($curl);
  }else{
    $resultado = json_decode($resultado);
    if($resultado->success){
      $cantidad = $resultado->data;
      // Insertamos comrobante
      $id_comprobante = insertar_comprobante($con, $cantidad, $fecha, $glosa, $tipo_cambio, $moneda);
      if($id_comprobante > 0){// comprobante insertado
        // Insertamos asientos
        $res_asientos = insertar_asientos($con, $monto, $id_comprobante, $forma_pago, $fact_reten, $cod_inventario);
        if($res_asientos != -1){

        }else{

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
} else {
  $response['code'] = 400;
  $response['message'] = '[Bad Request]: Error en el tipo de petición (Requerido POST)';
}
echo json_encode($response);
?>


<?php
// Funciones
function insertar_comprobante($con, $nro, $fecha, $glosa, $tipo_cambio, $moneda){
  $sql = "INSERT INTO tblComprobantes (numero, fecha, glosa, tipo, tipoCambio, moneda) VALUES (?, ?, ?, 'EGRESO', ?, ?);";
  $stmt = sqlsrv_query($con, $sql, array($nro, $fecha, $glosa, $tipo_cambio, $moneda));
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

function peticion_nro_comprobante($fecha){
  $url = 'http://'.$_SERVER['SERVER_NAME'].'/contabilidad/Comprobantes/services/obtener_numero_comprobante.php?fecha='.urlencode($fecha).'&tipo=EGRESO';
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $resultado = curl_exec($curl);
  return $resultado;
}

function insertar_asientos($con, $monto, $id_comprobante, $forma_pago, $fact_reten, $cod_inventario){
  if($fact_reten == 'si'){ // Con factura
    $cuenta_efect = $forma_pago == 'caja' ? 6 : 10; // caja -> caja central | banco -> bisa
    $cuenta_inventario = getIdCuentaInventario($cod_inventario);
    if($cuenta_inventario != -1){
      $cuenta_iva = 356;
      $iva = round($monto * 0.13, 2);
      $iva_inventario = round($monto - $iva, 2);
      $sql = "INSERT INTO tblAsientos (idComprobante, idCuenta, referencia, haber, debe, bco, cheque) VALUES (?, ?, '', ?, 0, '', ''), (?, ?, '', 0, ?, '', ''), (?, ?, '', 0, ?, '', '');";
      $params = array($id_comprobante, $cuenta_efect, $monto, $id_comprobante, $cuenta_inventario, $iva_inventario, $id_comprobante, $cuenta_iva, $iva);
      $stmt = sqlsrv_query($con, $sql, $params);
      if($stmt){
        return 1;
      }else{
        return -1;
      }
    }else{
      return -1;
    }
  }else{ // sin factura
    $cuenta_efect = $forma_pago == 'caja' ? 6 : 10; // caja -> caja central | banco -> bisa
    $cuenta_inventario = getIdCuentaInventario($cod_inventario);
    if($cuenta_inventario != -1){
      $cuenta_iva = 356;
      $cuenta_reten = 574; // retencion it 3%
      $cuentaiue = 576; // retencion bienes
      $reten5 = round($monto - ($monto * 0.05), 2);
      $reten3 = round($monto - ($monto * 0.03), 2);
      $reten8 = round($monto - ($monto * 0.08), 2);
      $sql = "INSERT INTO tblAsientos (idComprobante, idCuenta, referencia, haber, debe, bco, cheque) VALUES (?, ?, '', ?, 0, '', ''), (?, ?, '', 0, ?, '', ''), (?, ?, '', ?, 0, '', ''), (?, ?, '', ?, 0, '', '');";
      $params = array($id_comprobante, $cuenta_efect, $reten8, $id_comprobante, $cuenta_inventario, $monto, $id_comprobante, $cuentaiue, $reten5, $id_comprobante, $cuenta_reten, $reten3);
      $stmt = sqlsrv_query($con, $sql, $params);
      if($stmt){
        return 1;
      }else{
        return -1;
      }
    }else{
      return -1;
    }
  }
}

function getIdCuentaInventario($cod_inventario){
  // cuentas rango-> idCuenta <materia prima, pre-elaborados-prod-terminado-envases>
  $cta_inventario = array('1000'=>362, '2000'=>374, '3000'=>368, '4000'=>372);
  $cod_inventario = (int)$cod_inventario;
  $idInv = -1;
  if($cod_inventario >= 1000 && $cod_inventario < 2000){
    $idInv = $cta_inventario['1000'];
  }else if($cod_inventario >= 2000 && $cod_inventario < 3000){
    $idInv = $cta_inventario['2000'];
  }else if($cod_inventario >= 3000 && $cod_inventario < 4000){
    $idInv = $cta_inventario['3000'];
  }else if($cod_inventario >= 4000 && $cod_inventario < 5000){
    $idInv = $cta_inventario['4000'];
  }
  return $idInv;
}
?>