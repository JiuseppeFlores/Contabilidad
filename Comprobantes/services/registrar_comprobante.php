<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['tipo']) && isset($_POST['fecha'])){
            // Datos a registrar en la BD
            $nro_comprobante = $_POST['nro_comprobante'];
            $tipo = $_POST['tipo'];
            $fecha = $_POST['fecha'];
            $tipo_cambio = $_POST['tipo_cambio'];
            $moneda = $_POST['moneda'];
            $id_proyecto = $_POST['proyecto'];
            $cancelado = $_POST['cancelado'];
            $nit_ci = $_POST['nit_ci'];
            $nro_recibo = $_POST['nro_recibo'];
            $glosa = $_POST['glosa'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "INSERT INTO tblComprobantes (numero,tipo,fecha,tipo_cambio,moneda,idProyecto,cancelado,nit_ci,nro_recibo,glosa) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? ) ;";
            $params = array($nro_comprobante,$tipo,$fecha,$tipo_cambio,$moneda,$id_proyecto,$cancelado,$nit_ci,$nro_recibo,$glosa);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if($stmt === false){
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }else{
                $sql = "SELECT IDENT_CURRENT('tblComprobantes') as id;";
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $stmt = sqlsrv_query( $con, $sql , $params, $options );
                if( $stmt ){
                    $id_comprobante = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC )['id'];
                    $nro_asientos = isset($_POST['total_asientos']) ? intval($_POST['total_asientos']) : 0;
                    for( $i = 0 ; $i < $nro_asientos ; $i++ ){
                        $cuenta = $_POST['cuenta'][$i];
                        $referencia = $_POST['referencia'][$i];
                        $cc = $_POST['cc'][$i];
                        $debe = $_POST['debe'][$i];
                        $haber = $_POST['haber'][$i];
                        $banco = $_POST['banco'][$i];
                        $cheque = $_POST['cheque'][$i];

                        $sql = "INSERT INTO tblAsientos (idComprobante,idCuenta,referencia,cc,debe,haber,bco,cheque) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? ) ;";
                        $params = array($id_comprobante,$cuenta,$referencia,$cc,$debe,$haber,$banco,$cheque);
                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $stmt = sqlsrv_query( $con, $sql , $params, $options );
                        if( $stmt === false ){
                            $errors = sqlsrv_errors();
                            foreach( $errors as $error ) {
                                $response['message'] .= $error[ 'message']." , ";
                            }
                        }
                    }
                    $response['success'] = true;
                    $response['message'] .= 'Comprobante registrado con éxito.';
                }else{
                    $errors = sqlsrv_errors();
                    foreach( $errors as $error ) {
                        $response['message'] .= $error[ 'message']." , ";
                    }
                }
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: tipo y fecha.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>