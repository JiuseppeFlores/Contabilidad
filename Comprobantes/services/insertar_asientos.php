<?php
include_once '../../conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $idComprobante = $_POST['idComprobante'];
  $asientos = $_POST['asientos'];
  $sc = '';
  $asientos = $_POST['asientos'];
  foreach ($asientos as $value) {
    $idCuenta = $value['idCuenta'];
    $ref = $value['referencia'];
    $debe = $value['debe'];
    $haber = $value['haber'];
    $debeDolar = $value['debeDolar'];
    $haberDolar = $value['haberDolar'];
    $bco = $value['bco'];
    $cheque = $value['cheque'];
    $sc .= "($idComprobante, $idCuenta, '$ref', $debe, $haber, $debeDolar, $haberDolar, '$bco', '$cheque'),";
  }
  $sc = substr($sc, 0, -1);
  $sql = "INSERT INTO tblAsientos(idComprobante, idCuenta, referencia, debe, haber, debeDolar, haberDolar, bco, cheque) VALUES ";
  $stmt = sqlsrv_query($con, $sql.$sc);
  if($stmt){
    echo json_encode(array('ok'=>true, 'msg'=> 'Asientos Insertados'));
  }else{
    echo json_encode(array('ok'=>false, 'msg'=> 'Error al insertar los asientos'));
  }
}else{
  echo json_encode(array('ok'=>false, 'msg'=> 'Peticion Diferente a post'));
}
?>