<?php

include("../../conexion.php");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['tipo']) && isset($_POST['fecha'])) {
    // Datos a registrar en la BD
    $nro_comprobante = $_POST['nro_comprobante'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $tipo_cambio = $_POST['tipo_cambio'];
    $moneda = $_POST['moneda'];
    $id_proyecto = null;
    $detalle = $_POST['detalle'];
    $nit_ci = $_POST['nit_ci'];
    $nro_recibo = $_POST['nro_recibo'];
    $glosa = $_POST['glosa'];
    $msgPdf = 'SIN ARCHIVO PDF';
    $filename = 'no';
    $idUsuario = isset($_COOKIE['conta_id_user']) ? $_COOKIE['conta_id_user'] : 0 ;
    if (isset($_POST['pdfFile'])) {
      // Obtén el contenido del PDF en base64 enviado desde el cliente
      $base64Data = $_POST['pdfFile'];
      $pdfData = base64_decode($base64Data);
      // Genera un nombre único para el archivo PDF
      $filename = $_COOKIE['conta_subdomain'].'-' . time() . '.pdf';
      $uploadPath = '../Files/' . $filename;
      // Guarda el archivo PDF en el servidor
      if (file_put_contents($uploadPath, $pdfData)) {
        $msgPdf = 'OK';
      } else {
        $msgPdf = 'NO OK';
      }
    }
    if (isset($_POST['facts'])) {
      $msgPdf .= ' - FACTURA ENCONTRADA  ';
      $arrFacts = json_decode($_POST['facts'], true);
    }else{
      $arrFacts = array();
    }
    // Consulta para insertar los nuevos registros ala tabla
    $sql = "INSERT INTO tblComprobantes (numero,tipo,fecha,tipoCambio,moneda,idProyecto,cancelado,nitCi,nroRecibo,glosa,filepdf,idUsuario) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? ) ;";
    $params = array($nro_comprobante, $tipo, $fecha, $tipo_cambio, $moneda, $id_proyecto, $detalle, $nit_ci, $nro_recibo, $glosa, $filename, $idUsuario);
    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $stmt = sqlsrv_query($con, $sql, $params, $options);
    if ($stmt === false) {
      $errors = sqlsrv_errors();
      foreach ($errors as $error) {
        $response['message'] .= $error['message'] . " , ";
      }
    } else {
      $sql = "SELECT IDENT_CURRENT('tblComprobantes') as id;";
      $params = array();
      $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
      $stmt = sqlsrv_query($con, $sql, $params, $options);
      if ($stmt) {
        $id_comprobante = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['id'];
        $nro_asientos = isset($_POST['total_asientos']) ? intval($_POST['total_asientos']) : 0;
        
        // el mismo de asientos, la misma 
        for ($i = 0; $i < $nro_asientos; $i++) {
          $cuenta = $_POST['cuenta'][$i];
          $referencia = $_POST['referencia'][$i];
          $debe = $_POST['debe'][$i];
          $haber = $_POST['haber'][$i];
          $debeDolar = $_POST['debe_dolar'][$i];
          $haberDolar = $_POST['haber_dolar'][$i];
          $banco = $_POST['banco'][$i];
          $cheque = $_POST['cheque'][$i];
          $idFactura = 0;
          // Verificamos que existe una factura asociada a este asiento
          if(isset($arrFacts[$i])){
            // El asiento i tiene factura, entonces agregamos
            $sql = "INSERT INTO tblFacturaCompra (nitProveedor, nroFactura, codAutorizacion, facturaNueva, url) VALUES ( ? , ? , ? , ? , ? ) ;";
            if($arrFacts[$i]['nueva'] == 'no'){
              $params = array($arrFacts[$i]['nit'], $arrFacts[$i]['nroFact'], $arrFacts[$i]['codAuto'], $arrFacts[$i]['nueva'], '');
            }else{
              $params = array($arrFacts[$i]['nit'], $arrFacts[$i]['nroFact'], $arrFacts[$i]['codAuto'], 'si', urldecode($arrFacts[$i]['nueva']));
            }
            $stmt = sqlsrv_query($con, $sql, $params);
            if ($stmt) {
              $sql_id = "SELECT IDENT_CURRENT('tblFacturaCompra') as id;";
              $stmt_id = sqlsrv_query($con, $sql_id);
              if($stmt_id){
                $row = sqlsrv_fetch_array($stmt_id, SQLSRV_FETCH_ASSOC);
                $idFactura = $row['id'];
              }else{
                $err = '';
                $msgPdf .= ' - NO SE PUDO OBTENER EL ID DE LA FACTURA ';
                $errors = sqlsrv_errors();
                foreach ($errors as $error) {
                  $err .= $error['message'] . " , ";
                }  
                $msgPdf .= $err;
              }
            }else{
              $msgPdf .= ' - NO SE PUDO REGISTRAR LA FACTURA';
              $err = '';
              $msgPdf .= ' - NO SE PUDO OBTENER EL ID DE LA FACTURA ';
              $errors = sqlsrv_errors();
              foreach ($errors as $error) {
                $err .= $error['message'] . " , ";
              }  
              $msgPdf .= $err;
            }
          }else{
            $msgPdf .= ' - no existe factura asociada para el asiento '.$i.' ';
          }
          
          if($idFactura != 0){
            $sql = "INSERT INTO tblAsientos (idComprobante,idCuenta,referencia,debe,haber,debeDolar,haberDolar,bco,cheque,idFactCompra) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? ) ;";
            $params = array($id_comprobante, $cuenta, $referencia, $debe, $haber, $debeDolar, $haberDolar, $banco, $cheque, $idFactura);
          }else{
            $sql = "INSERT INTO tblAsientos (idComprobante,idCuenta,referencia,debe,haber,debeDolar,haberDolar,bco,cheque) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? ) ;";
            $params = array($id_comprobante, $cuenta, $referencia, $debe, $haber, $debeDolar, $haberDolar, $banco, $cheque);
          }
          $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
          $stmt = sqlsrv_query($con, $sql, $params, $options);
          if ($stmt === false) {
            $errors = sqlsrv_errors();
            foreach ($errors as $error) {
              $response['message'] .= $error['message'] . " , ";
            }
          }
        }
        $response['success'] = true;
        $response['message'] .= 'Comprobante registrado con éxito.';
      } else {
        $errors = sqlsrv_errors();
        foreach ($errors as $error) {
          $response['message'] .= $error['message'] . " , ";
        }
      }
    }
    $response['pdf'] = $msgPdf;
  } else {
    $response['message'] = 'Los siguientes campos son necesarios: tipo y fecha.';
  }
} else {
  $response['message'] = 'La solicitud no es de tipo POST';
}

echo json_encode($response);
?>