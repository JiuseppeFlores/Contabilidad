<?php

    include("../../conexion.php");

    $response = array('success' => false, 'message' => '');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['id']) && isset($_POST['codigo']) && isset($_POST['descripcion']) && isset($_POST['grupo'])){
            // Datos a registrar en la BD
            $idCuenta = $_POST['id'];
            $codigo = $_POST['codigo'];
            $descripcion = $_POST['descripcion'];
            $grupo = $_POST['grupo'];
            // Consulta para insertar los nuevos registros ala tabla
            $sql = "UPDATE tblCuentas 
                    SET codigo = ? ,
                        descripcion = ? ,
                        grupo = ? 
                    WHERE idCuenta = ? ;";
            $params = array($codigo,$descripcion,$grupo,$idCuenta);
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $con, $sql , $params, $options );
            if($stmt === false){
                $errors = sqlsrv_errors();
                foreach( $errors as $error ) {
                    $response['message'] .= $error[ 'message']." , ";
                }
            }else{
                $response['success'] = true;
                $response['message'] = 'Cuenta actualizada con éxito.';
            }
        }else{
            $response['message'] = 'Los siguientes campos son necesarios: Id, código, descripción y grupo.';
        }
    }else{
        $response['message'] = 'La solicitud no es de tipo POST';
    }

    echo json_encode($response);
?>