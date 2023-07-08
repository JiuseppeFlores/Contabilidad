<?php
// tipo_pago <CONTADO|CREDITO>
function registrar_venta($pin, $tipo_cambio, $moneda, $glosa, $factura, $forma_pago, $tipo_pago, $monto, $descuento, $rol_usuario, $nombre_usuario, $cliente){
  $url = 'http://177.222.51.52:86/Contabilidad/php/services/registrar_venta.php';
  $datos = [
    "pin" => $pin,
    "tipo_cambio" => $tipo_cambio,
    "moneda" => $moneda,
    "glosa" => $glosa,
    "factura" => $factura,
    "forma_pago" => $forma_pago,
    "tipo_pago" => $tipo_pago,
    "monto" => $monto,
    "descuento" => $descuento,
    "rol_usuario" => $rol_usuario,
    "nombre_usuario" => $nombre_usuario,
    "cliente" => $cliente
  ];
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($datos));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $resultado = curl_exec($curl);
  if($resultado === false){
    $response['message'] = '[OK]: Fallido: PETICION '.curl_error($curl);
  }else{
    $response = $resultado;    
  }
  return $response;
}

?>