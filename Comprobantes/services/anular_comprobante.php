<?php
include_once('../../conexion.php');
$response = array();
if($_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST['id'])){
    $sql = "UPDATE tblComprobantes SET estado = 'anulado' WHERE idComprobante = ?";
    $stmt = sqlsrv_query($con, $sql, array($_POST['id']));
    if($stmt){
      $response['code'] = 1;
      $response['message'] = 'Comprobante con id '.$_POST['id'].' anulado.';
    }else{
      $response['code'] = 0;
      $response['message'] = 'Error al anular comprobante con id '.$_POST['id'];
    }
  }else{
    $response['code'] = 0;
    $response['message'] = 'Requerido id del comprobante {id}';
  }
}else{
  $response['code'] = 0;
  $response['message'] = 'Peticion incorrecta';
}
echo json_encode($response);
?>