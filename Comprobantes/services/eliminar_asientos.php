<?php
include_once '../../conexion.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
  $arrIds = $_POST['ids'];
  $idsSQL = '';
  foreach ($arrIds as $value) {
    $idsSQL .= $value.',';
  }
  $idsSQL = rtrim($idsSQL,',');
  $sql = "DELETE FROM tblAsientos WHERE idAsiento IN ($idsSQL)";
  $stmt = sqlsrv_query($con,$sql);
  if($stmt){
    echo json_encode(array('ok'=>true, 'msg'=> 'Registros Eliminados'));
  }else{
    echo json_encode(array('ok'=>false, 'msg'=> 'Error al Eliminar'));
  }
}else{
  echo json_encode(array('ok'=>false, 'msg'=> 'Peticion Diferente a post'));
}
?>