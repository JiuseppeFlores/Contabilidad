<?php
function login($pin){
  include '../../conexion_empresa.php';
  $sql = "SELECT TOP 1 * FROM tblBase WHERE pin = ? AND status like 'ACTIVO';";
  $stmt = sqlsrv_query($con_com, $sql, array($pin));
  if($stmt){
    if(sqlsrv_has_rows($stmt) > 0){
      $datos = array();
      $row = sqlsrv_fetch_array($stmt);
      $datos['base'] = $row['base'];
      $datos['server'] = $row['server'];
      return $datos;
    }else{
      return -1;
    }
  }else{
    return -1;
  }
}

?>