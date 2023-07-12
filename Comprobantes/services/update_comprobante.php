<?php
include_once '../../conexion.php';
$response = array("success" => 0, "message_global" => "");
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // print_r($_POST);
  $ok_comprobante = 0;
  if(isset($_POST['pdfFile'])){
    $res = updateComprobanteFile($con, $_POST['idComprobante'], $_POST['tipoComprobante'], $_POST['numero'], $_POST['fecha'], $_POST['tipoCambio'], $_POST['moneda'], $_POST['nroRecibo'], $_POST['comprobanteDetalle'], $_POST['comprobanteNit'], $_POST['glosa'], $_POST['pdfFile'], $_POST['existePDF']);
    if($res != -1){
      $ok_comprobante = 1;
      $response['message_global'] .= ' | Comprobante actualizado';
    }else{
      $response['message_global'] .= ' | Error Actualizar Comprobante ';
    }
  }else{
    $res = updateComprobante($con, $_POST['idComprobante'], $_POST['tipoComprobante'], $_POST['numero'], $_POST['fecha'], $_POST['tipoCambio'], $_POST['moneda'], $_POST['nroRecibo'], $_POST['comprobanteDetalle'], $_POST['comprobanteNit'], $_POST['glosa']);
    if($res != -1){
      $ok_comprobante = 1;
      $response['message_global'] .= ' | Comprobante actualizado';
    }else{
      $response['message_global'] .= ' | Error Actualizar Comprobante ';
    }
  }
  
  $res_asiento = updateAsientos($con, $_POST['asientos']);
  $ok_asiento = 0;
  if($res_asiento != -1){
    $ok_asiento = 1;
    $response['message_global'] .= ' | Asientos actualizados';
  } else {
    $response['message_global'] .= ' | Error Actualizar ASIENTOS ';
  }

  if($ok_comprobante == 1 && $ok_asiento == 1){
    $response['success'] = 1;
  }else{
    $response['success'] = 0;
  }
  echo json_encode($response);
}

?>

<?php
function updateComprobante($con, $idComprobante, $tipo, $numero, $fecha,$tipoCambio, $moneda, $nroRecibo, $detalle, $nit, $glosa){
  $sql = "UPDATE tblComprobantes SET numero = ?, tipo = ?, fecha = ?, tipoCambio = ?, moneda = ?, nitCi = ?, nroRecibo = ?, glosa = ?, cancelado = ? WHERE idComprobante = ?";
  $params = array($numero, $tipo, $fecha, $tipoCambio, $moneda, $nit, $nroRecibo, $glosa,$detalle, $idComprobante);
  $stmt = sqlsrv_query($con, $sql, $params);
  if($stmt){
    return 1;
  }else{
    return -1;
  }
}
function updateComprobanteFile($con, $idComprobante, $tipo, $numero, $fecha,$tipoCambio, $moneda, $nroRecibo, $detalle, $nit, $glosa, $file, $existe){
  $respuesta = 0;
  if($existe == 'x'){
    $base64Data = $file;
    $pdfData = base64_decode($base64Data);
    $filename = $_COOKIE['conta_subdomain'].'-' . time() . '.pdf';
    $uploadPath = '../Files/' . $filename;
    if (file_put_contents($uploadPath, $pdfData)) {
      $sql = "UPDATE tblComprobantes SET numero = ?, tipo = ?, fecha = ?, tipoCambio = ?, moneda = ?, nitCi = ?, nroRecibo = ?, glosa = ?, filepdf = ?, cancelado = ? WHERE idComprobante = ?";
      $params = array($numero, $tipo, $fecha, $tipoCambio, $moneda, $nit, $nroRecibo, $glosa, $filename, $detalle, $idComprobante);
      $stmt = sqlsrv_query($con, $sql, $params);
      if($stmt){
        $respuesta = 1;
      }else{
        $respuesta = -1;  
      }
    } else {
      $respuesta = -1;
    }
  }else{
    $base64Data = $file;
    $pdfData = base64_decode($base64Data);
    $uploadPath = '../Files/' . $existe;
    if (file_put_contents($uploadPath, $pdfData)) {
      $sql = "UPDATE tblComprobantes SET numero = ?, tipo = ?, fecha = ?, tipoCambio = ?, moneda = ?, nitCi = ?, nroRecibo = ?, glosa = ?, filepdf = ? WHERE idComprobante = ?";
      $params = array($numero, $tipo, $fecha, $tipoCambio, $moneda, $nit, $nroRecibo, $glosa, $existe, $idComprobante);
      $stmt = sqlsrv_query($con, $sql, $params);
      if($stmt){
        $respuesta = 1;
      }else{
        $respuesta = -1;  
      }
    } else {
      $respuesta = -1;
    }
  }
  return $respuesta;
}

function updateAsientos($con, $asientos){
  $res = 1;
  foreach($asientos as $asiento){
    $sql = "UPDATE tblAsientos SET idCuenta = ?, referencia = ?, debe = ?, haber = ?, bco = ?, cheque = ?, debeDolar = ?, haberDolar = ? WHERE idAsiento = ?";
    $params = array($asiento['idCuenta'], $asiento['referencia'], $asiento['debe'], $asiento['haber'], $asiento['bco'], $asiento['cheque'], $asiento['debeDolar'], $asiento['haberDolar'], $asiento['idAsiento']);
    $stmt = sqlsrv_query($con, $sql, $params);
    if($stmt && $res > 0){
      $res = 1;
    }else{
      $res = -1;
      break;
    }
  }
  return $res;
}
?>